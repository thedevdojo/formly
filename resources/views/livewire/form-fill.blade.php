<?php

use App\Enums\FieldType;
use App\Models\Form;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Form $form;

    #[Locked]
    public bool $preview = false;

    /** @var array<string, mixed> */
    public array $answers = [];

    public bool $submitted = false;

    public function mount(Form $form, bool $preview = false): void
    {
        $this->form = $form;
        $this->preview = $preview;
        $this->resetAnswers();
    }

    public function submit(): void
    {
        if ($this->preview) {
            $this->dispatch('notify', message: 'Submissions are disabled in draft preview.', type: 'error');

            return;
        }

        abort_unless($this->form->isPublished(), 403);

        // Basic flood protection: 10 submissions per minute per IP per form.
        $throttleKey = 'form-submit:'.$this->form->id.':'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $this->addError('answers', 'Too many submissions — please try again in a minute.');

            return;
        }

        $rules = [];
        $attributes = [];

        foreach ($this->form->inputFields() as $field) {
            $type = FieldType::from($field['type']);
            $rules['answers.'.$field['id']] = $type->rules($field);
            $attributes['answers.'.$field['id']] = '"'.($field['label'] ?: $type->label()).'"';
        }

        $validated = $this->validate($rules, [], $attributes);

        RateLimiter::hit($throttleKey, 60);

        $this->form->entries()->create([
            'answers' => $validated['answers'],
            'meta' => [
                'ip' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 500),
                'referrer' => substr((string) request()->headers->get('referer'), 0, 500),
            ],
        ]);

        $this->submitted = true;
    }

    public function fillAgain(): void
    {
        $this->submitted = false;
        $this->resetAnswers();
    }

    protected function resetAnswers(): void
    {
        $this->answers = [];

        foreach ($this->form->inputFields() as $field) {
            $this->answers[$field['id']] = $field['type'] === FieldType::Checkboxes->value ? [] : '';
        }
    }
};

?>

<div class="flex flex-1 flex-col">
    @if ($submitted)
        {{-- ====================== Thank you ====================== --}}
        <div class="card relative flex flex-1 flex-col items-center justify-center overflow-hidden rounded-3xl px-8 py-20 text-center sm:px-14">
            <x-doodle name="sparkle" class="absolute left-[12%] top-12 size-10 -rotate-12 text-ink max-sm:hidden" />
            <x-doodle name="bubble-heart" class="absolute right-[10%] top-16 size-14 rotate-6 text-ink max-sm:hidden" />
            <x-doodle name="loop" class="absolute bottom-10 left-[14%] size-12 text-ink max-sm:hidden" />
            <span class="flex size-16 items-center justify-center rounded-full bg-accent-soft text-accent">
                <x-doodle name="check-flourish" class="size-9" />
            </span>
            <h1 class="mt-6 text-4xl font-extrabold tracking-tight">{{ $form->setting('success_title') }}</h1>
            <p class="mx-auto mt-3 max-w-sm leading-relaxed text-muted">{{ $form->setting('success_message') }}</p>
            <button wire:click="fillAgain" class="btn btn-ghost btn-sm mt-8">Submit another response</button>
        </div>
    @else
        {{-- ====================== The form ====================== --}}
        <form wire:submit="submit" class="card rounded-3xl px-7 py-10 sm:px-14 sm:py-14" novalidate>
            <h1 class="text-center text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $form->name }}</h1>

            @error('answers')
                <p class="mt-4 rounded-xl border border-bad/30 bg-bad-soft px-4 py-2.5 text-sm font-medium text-bad">{{ $message }}</p>
            @enderror

            @php $questionNumber = 0; @endphp
            <div class="mt-8 space-y-8 border-t border-line pt-9">
                @foreach ($form->fields ?? [] as $field)
                    @php
                        $type = \App\Enums\FieldType::tryFrom($field['type']);
                        $id = $field['id'];

                        if ($type !== null && $type->isInput()) {
                            $questionNumber++;
                        }
                    @endphp

                    @if ($type === null)
                        @continue
                    @endif

                    <div wire:key="fill-{{ $id }}">
                        @if ($type === \App\Enums\FieldType::Statement)
                            <div class="{{ ! $loop->first ? 'border-t border-line pt-8' : '' }}">
                                @if (filled($field['label']))
                                    <h2 class="text-xl font-extrabold tracking-tight">{{ $field['label'] }}</h2>
                                @endif
                                @if (filled($field['body'] ?? ''))
                                    <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $field['body'] }}</p>
                                @endif
                            </div>
                        @else
                            <label class="block text-[15px] font-bold" for="field-{{ $id }}">
                                {{ $questionNumber }}. {{ $field['label'] ?: $type->label() }}
                                @if ($field['required'] ?? false)
                                    <span class="text-accent" aria-hidden="true">*</span>
                                @endif
                            </label>
                            @if (filled($field['help'] ?? ''))
                                <p class="mt-0.5 text-xs text-muted">{{ $field['help'] }}</p>
                            @endif

                            <div class="mt-2.5">
                                @switch($type)
                                    @case(\App\Enums\FieldType::LongText)
                                        <textarea
                                            id="field-{{ $id }}"
                                            wire:model="answers.{{ $id }}"
                                            rows="4"
                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                            class="input resize-y !py-3 @error('answers.'.$id) !border-bad @enderror"
                                        ></textarea>
                                        @break

                                    @case(\App\Enums\FieldType::Select)
                                        <select id="field-{{ $id }}" wire:model="answers.{{ $id }}" class="input @error('answers.'.$id) !border-bad @enderror">
                                            <option value="">Choose an option…</option>
                                            @foreach ($field['options'] ?? [] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @break

                                    @case(\App\Enums\FieldType::MultipleChoice)
                                        <div class="space-y-2" role="radiogroup">
                                            @foreach ($field['options'] ?? [] as $option)
                                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 text-sm font-medium transition has-checked:border-accent has-checked:bg-accent-soft border-line bg-surface hover:border-line-strong">
                                                    <input type="radio" wire:model="answers.{{ $id }}" value="{{ $option }}" class="peer sr-only">
                                                    <span class="flex size-[18px] shrink-0 items-center justify-center rounded-full border border-line-strong transition peer-checked:border-accent">
                                                        <span class="size-2.5 scale-0 rounded-full bg-accent transition peer-checked:scale-100"></span>
                                                    </span>
                                                    {{ $option }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @break

                                    @case(\App\Enums\FieldType::Checkboxes)
                                        <div class="space-y-2">
                                            @foreach ($field['options'] ?? [] as $option)
                                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 text-sm font-medium transition has-checked:border-accent has-checked:bg-accent-soft border-line bg-surface hover:border-line-strong">
                                                    <input type="checkbox" wire:model="answers.{{ $id }}" value="{{ $option }}" class="peer sr-only">
                                                    <span class="flex size-[18px] shrink-0 items-center justify-center rounded-md border border-line-strong bg-surface text-white transition peer-checked:border-accent peer-checked:bg-accent">
                                                        <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg>
                                                    </span>
                                                    {{ $option }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @break

                                    @case(\App\Enums\FieldType::Rating)
                                        @php $max = (int) ($field['max'] ?? 5); @endphp
                                        <div
                                            x-data="{ value: $wire.entangle('answers.{{ $id }}'), hover: 0 }"
                                            class="flex items-center gap-1"
                                            role="radiogroup"
                                            aria-label="Rating from 1 to {{ $max }}"
                                        >
                                            @foreach (range(1, $max) as $star)
                                                <button
                                                    type="button"
                                                    x-on:click="value = {{ $star }}"
                                                    x-on:mouseenter="hover = {{ $star }}"
                                                    x-on:mouseleave="hover = 0"
                                                    class="text-line-strong transition-transform hover:scale-110"
                                                    :class="(hover ? {{ $star }} <= hover : {{ $star }} <= Number(value)) && '!text-accent'"
                                                    aria-label="{{ $star }}"
                                                >
                                                    <svg class="size-8" viewBox="0 0 24 24" :fill="(hover ? {{ $star }} <= hover : {{ $star }} <= Number(value)) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.5"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                                                </button>
                                            @endforeach
                                            <span class="ml-2 text-sm font-semibold text-muted" x-show="Number(value) > 0" x-cloak><span x-text="value"></span>/{{ $max }}</span>
                                        </div>
                                        @break

                                    @case(\App\Enums\FieldType::Date)
                                        <input id="field-{{ $id }}" type="date" wire:model="answers.{{ $id }}" class="input max-w-52 @error('answers.'.$id) !border-bad @enderror">
                                        @break

                                    @case(\App\Enums\FieldType::Number)
                                        <input id="field-{{ $id }}" type="number" wire:model="answers.{{ $id }}" placeholder="{{ $field['placeholder'] ?? '' }}" class="input max-w-52 @error('answers.'.$id) !border-bad @enderror">
                                        @break

                                    @case(\App\Enums\FieldType::Email)
                                        <input id="field-{{ $id }}" type="email" wire:model="answers.{{ $id }}" placeholder="{{ $field['placeholder'] ?: 'you@example.com' }}" class="input @error('answers.'.$id) !border-bad @enderror">
                                        @break

                                    @case(\App\Enums\FieldType::Phone)
                                        <input id="field-{{ $id }}" type="tel" wire:model="answers.{{ $id }}" placeholder="{{ $field['placeholder'] ?? '' }}" class="input max-w-72 @error('answers.'.$id) !border-bad @enderror">
                                        @break

                                    @case(\App\Enums\FieldType::Url)
                                        <input id="field-{{ $id }}" type="url" wire:model="answers.{{ $id }}" placeholder="{{ $field['placeholder'] ?: 'https://' }}" class="input @error('answers.'.$id) !border-bad @enderror">
                                        @break

                                    @default
                                        <input id="field-{{ $id }}" type="text" wire:model="answers.{{ $id }}" placeholder="{{ $field['placeholder'] ?? '' }}" class="input @error('answers.'.$id) !border-bad @enderror">
                                @endswitch

                                @error('answers.'.$id)
                                    <p class="mt-1.5 text-xs font-medium text-bad">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-10 flex items-center gap-4">
                <button type="submit" class="btn btn-ink btn-lg" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">{{ $form->setting('submit_label') }}</span>
                    <span wire:loading wire:target="submit">Sending…</span>
                </button>
                @if ($preview)
                    <span class="text-xs font-medium text-warn">Submissions disabled in preview</span>
                @endif
            </div>
        </form>
    @endif
</div>
