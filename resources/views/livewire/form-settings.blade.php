<?php

use App\Models\Form;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Form $form;

    public string $submit_label = '';

    public string $success_title = '';

    public string $success_message = '';

    public string $closed_message = '';

    public string $status = Form::STATUS_DRAFT;

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'submit_label' => 'required|string|max:40',
            'success_title' => 'required|string|max:120',
            'success_message' => 'nullable|string|max:500',
            'closed_message' => 'nullable|string|max:500',
        ];
    }

    public function mount(Form $form): void
    {
        $this->form = $form;
        $this->status = $form->status;
        $this->submit_label = $form->setting('submit_label');
        $this->success_title = $form->setting('success_title');
        $this->success_message = $form->setting('success_message') ?? '';
        $this->closed_message = $form->setting('closed_message') ?? '';
    }

    public function save(): void
    {
        $this->validate();

        $this->form->update([
            'settings' => array_merge(Form::defaultSettings(), [
                'submit_label' => $this->submit_label,
                'success_title' => $this->success_title,
                'success_message' => $this->success_message,
                'closed_message' => $this->closed_message,
            ]),
        ]);

        $this->dispatch('notify', message: 'Settings saved.');
    }

    public function setStatus(string $status): void
    {
        if (! in_array($status, [Form::STATUS_DRAFT, Form::STATUS_PUBLISHED, Form::STATUS_CLOSED], true)) {
            return;
        }

        $this->form->update([
            'status' => $status,
            'published_at' => $status === Form::STATUS_PUBLISHED ? ($this->form->published_at ?? now()) : $this->form->published_at,
        ]);
        $this->status = $status;

        $this->dispatch('notify', message: match ($status) {
            Form::STATUS_PUBLISHED => 'Form is live.',
            Form::STATUS_CLOSED => 'Form closed — no new responses will be accepted.',
            default => 'Form reverted to draft.',
        });
    }

    public function deleteForm()
    {
        $this->form->delete();

        return $this->redirect(route('dashboard'));
    }
};

?>

<div class="mx-auto max-w-2xl space-y-6">

    {{-- Availability --}}
    <section class="card rounded-2xl p-7">
        <h2 class="font-display text-lg font-extrabold">Availability</h2>
        <p class="mt-1 text-sm text-muted">Control who can submit responses right now.</p>

        <div class="mt-5 grid gap-2.5 sm:grid-cols-3">
            @foreach ([
                Form::STATUS_DRAFT => ['label' => 'Draft', 'desc' => 'Only you can see it', 'icon' => 'pencil'],
                Form::STATUS_PUBLISHED => ['label' => 'Live', 'desc' => 'Accepting responses', 'icon' => 'globe'],
                Form::STATUS_CLOSED => ['label' => 'Closed', 'desc' => 'Link works, no new entries', 'icon' => 'lock'],
            ] as $value => $option)
                <button
                    wire:click="setStatus('{{ $value }}')"
                    class="rounded-xl border p-4 text-left transition {{ $status === $value ? 'border-accent bg-accent-soft' : 'border-line bg-surface hover:border-line-strong' }}"
                >
                    <span class="flex items-center gap-2 text-sm font-bold {{ $status === $value ? 'text-accent' : '' }}">
                        <x-icon :name="$option['icon']" class="size-4" />
                        {{ $option['label'] }}
                    </span>
                    <span class="mt-1 block text-xs text-muted">{{ $option['desc'] }}</span>
                </button>
            @endforeach
        </div>
    </section>

    {{-- Submission experience --}}
    <section class="card rounded-2xl p-7">
        <h2 class="font-display text-lg font-extrabold">Submission experience</h2>
        <p class="mt-1 text-sm text-muted">What respondents see when they interact with your form.</p>

        <div class="mt-6 space-y-5">
            <div>
                <label class="label" for="submit_label">Submit button label</label>
                <input id="submit_label" type="text" wire:model="submit_label" class="input max-w-50" maxlength="40">
                @error('submit_label') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="success_title">Thank-you headline</label>
                <input id="success_title" type="text" wire:model="success_title" class="input" maxlength="120">
                @error('success_title') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="success_message">Thank-you message</label>
                <textarea id="success_message" rows="3" wire:model="success_message" class="input resize-none" maxlength="500"></textarea>
                @error('success_message') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="closed_message">Closed-form message</label>
                <textarea id="closed_message" rows="2" wire:model="closed_message" class="input resize-none" maxlength="500"></textarea>
                <p class="mt-1 text-xs text-subtle">Shown when the form is closed but someone visits the link.</p>
                @error('closed_message') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end border-t border-line pt-5">
                <button wire:click="save" class="btn btn-ink">
                    <span wire:loading.remove wire:target="save">Save changes</span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Link --}}
    <section class="card rounded-2xl p-7" x-data="{ copied: false }">
        <h2 class="font-display text-lg font-extrabold">Form link</h2>
        <p class="mt-1 text-sm text-muted">Your form's permanent public address.</p>
        <div class="mt-4 flex items-center gap-2">
            <input type="text" readonly value="{{ $form->publicUrl() }}" class="input flex-1 font-mono !text-xs" x-on:focus="$el.select()">
            <button
                x-on:click="navigator.clipboard.writeText('{{ $form->publicUrl() }}'); copied = true; setTimeout(() => copied = false, 1600)"
                class="btn btn-outline btn-sm shrink-0"
            >
                <span x-show="!copied">Copy</span>
                <span x-show="copied" x-cloak class="flex items-center gap-1 text-good"><x-icon name="check" class="size-3.5" /> Copied</span>
            </button>
        </div>
    </section>

    {{-- Danger zone --}}
    <section class="card rounded-2xl border-bad/20 p-7">
        <h2 class="font-display text-lg font-extrabold text-bad">Danger zone</h2>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold">Delete this form</p>
                <p class="text-xs text-muted">Deletes the form and all of its responses. There is no undo.</p>
            </div>
            <button
                wire:click="deleteForm"
                wire:confirm="Delete “{{ $form->name }}” and ALL of its responses? This cannot be undone."
                class="btn btn-danger btn-sm"
            >
                <x-icon name="trash" class="size-4" /> Delete form
            </button>
        </div>
    </section>
</div>
