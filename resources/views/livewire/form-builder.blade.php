<?php

use App\Enums\FieldType;
use App\Models\Form;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Form $form;

    public string $name = '';

    /** @var list<array<string, mixed>> */
    public array $fields = [];

    public string $status = Form::STATUS_DRAFT;

    public ?string $lastAdded = null;

    public function mount(Form $form): void
    {
        $this->form = $form;
        $this->name = $form->name;
        $this->fields = $form->fields ?? [];
        $this->status = $form->status;
    }

    public function updated(string $property): void
    {
        if ($property === 'name' || str_starts_with($property, 'fields')) {
            $this->persist();
        }
    }

    public function persist(): void
    {
        if (trim($this->name) === '') {
            $this->name = 'Untitled form';
        }

        $this->form->update([
            'name' => $this->name,
            'fields' => array_values($this->fields),
        ]);
    }

    public function addField(string $type, ?int $after = null): void
    {
        $field = Form::makeField(FieldType::from($type));

        if ($after === null) {
            $this->fields[] = $field;
        } else {
            array_splice($this->fields, $after + 1, 0, [$field]);
        }

        $this->lastAdded = $field['id'];
        $this->persist();
    }

    public function removeField(string $fieldId): void
    {
        $this->fields = array_values(array_filter($this->fields, fn ($f) => $f['id'] !== $fieldId));
        $this->persist();
    }

    public function duplicateField(string $fieldId): void
    {
        foreach ($this->fields as $index => $field) {
            if ($field['id'] === $fieldId) {
                $copy = $field;
                $copy['id'] = 'fld_'.Str::lower(Str::random(8));
                array_splice($this->fields, $index + 1, 0, [$copy]);
                $this->lastAdded = $copy['id'];
                break;
            }
        }

        $this->persist();
    }

    public function sortFields(string $fieldId, int $position): void
    {
        $fields = array_values($this->fields);
        $index = collect($fields)->search(fn ($f) => $f['id'] === $fieldId);

        if ($index === false) {
            return;
        }

        $moved = array_splice($fields, $index, 1);
        array_splice($fields, $position, 0, $moved);

        $this->fields = $fields;
        $this->persist();
    }

    public function addOption(int $index): void
    {
        $this->fields[$index]['options'][] = 'Option '.(count($this->fields[$index]['options'] ?? []) + 1);
        $this->persist();
    }

    public function removeOption(int $index, int $optionIndex): void
    {
        if (count($this->fields[$index]['options'] ?? []) <= 1) {
            return;
        }

        array_splice($this->fields[$index]['options'], $optionIndex, 1);
        $this->persist();
    }

    public function publish(): void
    {
        if (count($this->form->inputFields()) === 0) {
            $this->dispatch('notify', message: 'Add at least one question before publishing.', type: 'error');

            return;
        }

        $this->form->update(['status' => Form::STATUS_PUBLISHED, 'published_at' => $this->form->published_at ?? now()]);
        $this->status = Form::STATUS_PUBLISHED;
        $this->dispatch('notify', message: 'Form published — share away!');
    }

    public function unpublish(): void
    {
        $this->form->update(['status' => Form::STATUS_DRAFT]);
        $this->status = Form::STATUS_DRAFT;
        $this->dispatch('notify', message: 'Form reverted to draft.');
    }
};

?>

<div
    x-data="{ addMenu: false, share: false }"
    x-on:keydown.slash.window="if (!['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)) { $event.preventDefault(); addMenu = true; $nextTick(() => $refs.addMenuPanel?.scrollIntoView({ block: 'nearest', behavior: 'smooth' })) }"
>
    {{-- ====================== Action bar ====================== --}}
    <div class="mx-auto flex max-w-2xl flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            @if ($status === Form::STATUS_PUBLISHED)
                <span class="chip border-good/30 bg-good-soft text-good"><span class="size-1.5 rounded-full bg-good"></span> Live</span>
            @elseif ($status === Form::STATUS_CLOSED)
                <span class="chip border-warn/30 bg-warn-soft text-warn">Closed</span>
            @else
                <span class="chip">Draft</span>
            @endif

            <span class="text-xs text-subtle">
                <span wire:loading.delay.shortest>Saving…</span>
                <span wire:loading.remove class="flex items-center gap-1"><x-icon name="check" class="size-3" /> Saved</span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ $form->publicUrl() }}" target="_blank" class="btn btn-ghost btn-sm">
                <x-icon name="eye" class="size-4" /> Preview
            </a>
            <button x-on:click="share = true" class="btn btn-outline btn-sm">
                <x-icon name="share" class="size-4" /> Share
            </button>
            @if ($status === Form::STATUS_PUBLISHED)
                <button wire:click="unpublish" class="btn btn-outline btn-sm">Unpublish</button>
            @else
                <button wire:click="publish" class="btn btn-ink btn-sm">
                    <x-icon name="send" class="size-4" /> Publish
                </button>
            @endif
        </div>
    </div>

    {{-- ====================== Document ====================== --}}
    <div class="mx-auto mt-6 max-w-2xl">
        <div class="card rounded-2xl px-7 py-9 sm:px-12 sm:py-12">

            {{-- Form title --}}
            <input
                type="text"
                wire:model.live.debounce.600ms="name"
                placeholder="Untitled form"
                class="input-bare font-display text-3xl font-extrabold tracking-tight"
                maxlength="120"
            >

            {{-- Blocks --}}
            <div class="mt-8 space-y-1" wire:sort="sortFields">
                @forelse ($fields as $index => $field)
                    @php $type = \App\Enums\FieldType::tryFrom($field['type']) ?? \App\Enums\FieldType::ShortText; @endphp

                    <div
                        wire:key="block-{{ $field['id'] }}"
                        wire:sort:item="{{ $field['id'] }}"
                        x-data="{ expanded: false }"
                        @if ($lastAdded === $field['id']) x-init="$nextTick(() => $el.querySelector('input[type=text]')?.focus())" @endif
                        class="group/block relative -mx-4 rounded-xl px-4 py-3.5 transition hover:bg-canvas"
                    >
                        {{-- Drag handle --}}
                        <button
                            wire:sort:handle
                            class="absolute -left-4 top-4 cursor-grab rounded-md p-1 text-subtle opacity-0 transition hover:bg-ink-soft hover:text-ink group-hover/block:opacity-100 active:cursor-grabbing"
                            aria-label="Drag to reorder"
                        >
                            <x-icon name="grip" class="size-4" />
                        </button>

                        {{-- Block toolbar --}}
                        <div wire:sort:ignore class="absolute -top-3 right-3 z-10 hidden items-center gap-0.5 rounded-lg border border-line bg-surface p-0.5 shadow-md group-hover/block:flex group-focus-within/block:flex">
                            @if ($type->isInput())
                                <label class="flex cursor-pointer items-center gap-1.5 rounded-md px-2 py-1.5 text-xs font-semibold text-muted transition hover:bg-ink-soft hover:text-ink" title="Required">
                                    <input type="checkbox" wire:model.live="fields.{{ $index }}.required" class="peer sr-only">
                                    <span class="flex h-4 w-7 items-center rounded-full bg-line-strong p-0.5 transition peer-checked:bg-accent">
                                        <span class="size-3 rounded-full bg-white shadow transition {{ ($field['required'] ?? false) ? 'translate-x-3' : '' }}"></span>
                                    </span>
                                    Required
                                </label>
                                <span class="h-4 w-px bg-line"></span>
                            @endif
                            <button x-on:click="expanded = !expanded" class="rounded-md p-1.5 text-muted transition hover:bg-ink-soft hover:text-ink" title="Block settings" :class="expanded && 'bg-ink-soft text-ink'">
                                <x-icon name="settings" class="size-3.5" />
                            </button>
                            <button wire:click="duplicateField('{{ $field['id'] }}')" class="rounded-md p-1.5 text-muted transition hover:bg-ink-soft hover:text-ink" title="Duplicate">
                                <x-icon name="duplicate" class="size-3.5" />
                            </button>
                            <button wire:click="removeField('{{ $field['id'] }}')" class="rounded-md p-1.5 text-muted transition hover:bg-bad-soft hover:text-bad" title="Delete">
                                <x-icon name="trash" class="size-3.5" />
                            </button>
                        </div>

                        {{-- Label --}}
                        <div class="flex items-start gap-2.5">
                            <span class="mt-1 flex size-6 shrink-0 items-center justify-center rounded-md bg-ink-soft text-muted" title="{{ $type->label() }}">
                                <x-icon :name="$type->icon()" class="size-3.5" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-baseline gap-1">
                                    <input
                                        type="text"
                                        wire:model.live.debounce.600ms="fields.{{ $index }}.label"
                                        placeholder="{{ $type === \App\Enums\FieldType::Statement ? 'Add a heading…' : 'Type your question…' }}"
                                        class="input-bare text-[15px] font-semibold"
                                        maxlength="255"
                                    >
                                    @if ($field['required'] ?? false)
                                        <span class="text-accent" aria-hidden="true">*</span>
                                    @endif
                                </div>

                                @if (filled($field['help'] ?? ''))
                                    <p class="mt-0.5 text-xs text-muted">{{ $field['help'] }}</p>
                                @endif

                                {{-- Type-specific preview / editor --}}
                                <div class="mt-2" wire:sort:ignore>
                                    @if ($type === \App\Enums\FieldType::Statement)
                                        <textarea
                                            wire:model.live.debounce.600ms="fields.{{ $index }}.body"
                                            placeholder="Add some text…"
                                            rows="2"
                                            class="input-bare resize-none text-sm text-muted"
                                        ></textarea>

                                    @elseif ($type->hasOptions())
                                        <div class="space-y-1.5">
                                            @foreach ($field['options'] ?? [] as $optionIndex => $option)
                                                <div class="group/option flex items-center gap-2" wire:key="opt-{{ $field['id'] }}-{{ $optionIndex }}">
                                                    @if ($type === \App\Enums\FieldType::Checkboxes)
                                                        <span class="size-4 shrink-0 rounded border border-line-strong bg-surface"></span>
                                                    @elseif ($type === \App\Enums\FieldType::MultipleChoice)
                                                        <span class="size-4 shrink-0 rounded-full border border-line-strong bg-surface"></span>
                                                    @else
                                                        <span class="w-4 shrink-0 text-center font-mono text-[11px] text-subtle">{{ $optionIndex + 1 }}</span>
                                                    @endif
                                                    <input
                                                        type="text"
                                                        wire:model.live.debounce.600ms="fields.{{ $index }}.options.{{ $optionIndex }}"
                                                        class="input-bare rounded-md border border-transparent px-2 py-1 text-sm transition hover:border-line focus:border-line-strong focus:bg-surface"
                                                        placeholder="Option {{ $optionIndex + 1 }}"
                                                    >
                                                    <button
                                                        wire:click="removeOption({{ $index }}, {{ $optionIndex }})"
                                                        class="rounded p-1 text-subtle opacity-0 transition hover:text-bad group-hover/option:opacity-100"
                                                        aria-label="Remove option"
                                                    >
                                                        <x-icon name="x" class="size-3.5" />
                                                    </button>
                                                </div>
                                            @endforeach
                                            <button wire:click="addOption({{ $index }})" class="ml-6 flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-semibold text-muted transition hover:bg-ink-soft hover:text-ink">
                                                <x-icon name="plus" class="size-3" /> Add option
                                            </button>
                                        </div>

                                    @elseif ($type === \App\Enums\FieldType::Rating)
                                        <div class="flex items-center gap-1 text-line-strong">
                                            @foreach (range(1, (int) ($field['max'] ?? 5)) as $star)
                                                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                                            @endforeach
                                        </div>

                                    @elseif ($type === \App\Enums\FieldType::LongText)
                                        <textarea
                                            wire:model.live.debounce.600ms="fields.{{ $index }}.placeholder"
                                            rows="2"
                                            placeholder="Type a placeholder for this field…"
                                            class="w-full resize-none rounded-lg border border-dashed border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-muted placeholder:text-subtle focus:border-ink focus:outline-none"
                                        ></textarea>

                                    @elseif ($type === \App\Enums\FieldType::Date)
                                        <div class="flex w-48 items-center gap-2 rounded-lg border border-dashed border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-subtle">
                                            <x-icon name="calendar" class="size-4" /> Pick a date
                                        </div>

                                    @else
                                        <input
                                            type="text"
                                            wire:model.live.debounce.600ms="fields.{{ $index }}.placeholder"
                                            placeholder="Type a placeholder for this field…"
                                            class="w-full rounded-lg border border-dashed border-line-strong bg-canvas px-3.5 py-2.5 text-sm text-muted placeholder:text-subtle focus:border-ink focus:outline-none"
                                        >
                                    @endif
                                </div>

                                {{-- Expanded block settings --}}
                                <div x-cloak x-show="expanded" x-collapse.duration.200ms wire:sort:ignore>
                                    <div class="mt-3 space-y-3 rounded-xl border border-line bg-canvas p-4">
                                        <div>
                                            <label class="label !mb-1 text-xs">Help text</label>
                                            <input type="text" wire:model.live.debounce.600ms="fields.{{ $index }}.help" placeholder="Add a hint shown under the question" class="input !py-2 text-xs">
                                        </div>
                                        @if ($type === \App\Enums\FieldType::Rating)
                                            <div>
                                                <label class="label !mb-1 text-xs">Scale</label>
                                                <select wire:model.live="fields.{{ $index }}.max" class="input w-28 !py-2 text-xs">
                                                    @foreach ([3, 5, 7, 10] as $max)
                                                        <option value="{{ $max }}">1–{{ $max }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <p class="text-[11px] text-subtle">Field type: {{ $type->label() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-line-strong px-6 py-10 text-center">
                        <x-doodle name="sparkle" class="mx-auto size-9 -rotate-12 text-accent" />
                        <p class="mt-3 font-bold">A blank page, full of potential</p>
                        <p class="mx-auto mt-1 max-w-xs text-sm text-muted">Add your first question below — or press <span class="kbd">/</span> anywhere.</p>
                    </div>
                @endforelse
            </div>

            {{-- Add block --}}
            <div class="relative mt-4" x-ref="addMenuPanel">
                <button
                    x-on:click="addMenu = !addMenu"
                    class="flex w-full items-center gap-2 rounded-xl border border-dashed border-line-strong px-4 py-3 text-sm font-semibold text-muted transition hover:border-ink hover:text-ink"
                >
                    <x-icon name="plus" class="size-4" />
                    Add a question
                    <span class="ml-auto font-mono text-xs text-subtle">/</span>
                </button>

                <div
                    x-cloak
                    x-show="addMenu"
                    x-on:click.outside="addMenu = false"
                    x-on:keydown.escape.window="addMenu = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute bottom-full left-0 z-30 mb-2 w-full rounded-2xl border border-line bg-surface p-4 shadow-2xl"
                >
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach (\App\Enums\FieldType::grouped() as $group => $types)
                            <div>
                                <p class="px-1 text-[11px] font-bold uppercase tracking-[0.12em] text-subtle">{{ $group }}</p>
                                <div class="mt-1.5 space-y-0.5">
                                    @foreach ($types as $fieldType)
                                        <button
                                            wire:click="addField('{{ $fieldType->value }}')"
                                            x-on:click="addMenu = false"
                                            class="flex w-full items-center gap-2.5 rounded-lg px-2 py-1.5 text-left text-sm font-medium transition hover:bg-accent-soft hover:text-ink"
                                        >
                                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-ink-soft text-muted">
                                                <x-icon :name="$fieldType->icon()" class="size-4" />
                                            </span>
                                            {{ $fieldType->label() }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Submit button preview --}}
            <div class="mt-9 border-t border-line pt-7">
                <span class="btn btn-ink pointer-events-none opacity-90">{{ $form->setting('submit_label') }}</span>
                <p class="mt-2 text-[11px] text-subtle">Customize the button label and thank-you screen in <a href="{{ route('forms.settings', ['form' => $form]) }}" class="font-semibold underline decoration-line-strong underline-offset-2 hover:text-ink">Settings</a>.</p>
            </div>
        </div>
    </div>

    {{-- ====================== Share modal ====================== --}}
    <template x-teleport="body">
        <div
            x-cloak
            x-show="share"
            x-on:keydown.escape.window="share = false"
            class="fixed inset-0 z-[80] flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
        >
            <div x-show="share" x-transition.opacity.duration.200ms class="absolute inset-0 bg-ink/40 backdrop-blur-sm" x-on:click="share = false"></div>

            <div
                x-show="share"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-md rounded-2xl border border-line bg-surface p-6 shadow-2xl"
            >
                <button x-on:click="share = false" class="absolute right-4 top-4 rounded-lg p-1.5 text-subtle transition hover:bg-ink-soft hover:text-ink" aria-label="Close">
                    <x-icon name="x" class="size-4" />
                </button>

                <h2 class="font-display text-xl font-extrabold">Share your form</h2>

                @if ($status === Form::STATUS_PUBLISHED)
                    <p class="mt-1 text-sm text-muted">Anyone with the link can respond.</p>

                    <div class="mt-5 space-y-4" x-data="{ copied: false, copiedEmbed: false }">
                        <div>
                            <label class="label">Link</label>
                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ $form->publicUrl() }}" class="input flex-1 font-mono !text-xs" x-on:focus="$el.select()">
                                <button
                                    x-on:click="navigator.clipboard.writeText('{{ $form->publicUrl() }}'); copied = true; setTimeout(() => copied = false, 1600)"
                                    class="btn btn-ink btn-sm shrink-0"
                                >
                                    <span x-show="!copied">Copy</span>
                                    <span x-show="copied" x-cloak class="flex items-center gap-1"><x-icon name="check" class="size-3.5" /> Copied</span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="label">Embed</label>
                            <textarea readonly rows="3" class="input resize-none font-mono !text-xs" x-on:focus="$el.select()">&lt;iframe src="{{ $form->publicUrl() }}" width="100%" height="600" frameborder="0"&gt;&lt;/iframe&gt;</textarea>
                        </div>
                    </div>
                @else
                    <p class="mt-1 text-sm text-muted">This form is still a draft. Publish it to get a shareable link.</p>
                    <button wire:click="publish" x-on:click="share = false" class="btn btn-ink mt-5 w-full">
                        <x-icon name="send" class="size-4" /> Publish now
                    </button>
                @endif
            </div>
        </div>
    </template>
</div>
