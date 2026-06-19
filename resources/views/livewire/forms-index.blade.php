<?php

use App\Models\Form;
use App\Models\FormEntry;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $search = '';

    #[Computed]
    public function forms()
    {
        return auth()->user()->forms()
            ->withCount('entries')
            ->when(trim($this->search) !== '', fn ($q) => $q->where('name', 'like', '%'.trim($this->search).'%'))
            ->orderByDesc('updated_at')
            ->get();
    }

    /**
     * @return array{forms: int, responses: int, thisWeek: int}
     */
    #[Computed]
    public function stats(): array
    {
        $formIds = auth()->user()->forms()->pluck('id');

        return [
            'forms' => $formIds->count(),
            'responses' => FormEntry::whereIn('form_id', $formIds)->count(),
            'thisWeek' => FormEntry::whereIn('form_id', $formIds)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];
    }

    public function createForm()
    {
        $form = Form::create([
            'user_id' => auth()->id(),
            'name' => 'Untitled form',
            'fields' => [],
        ]);

        return $this->redirect(route('forms.edit', ['form' => $form]));
    }

    public function duplicateForm(int $formId): void
    {
        $form = auth()->user()->forms()->findOrFail($formId);

        Form::create([
            'user_id' => auth()->id(),
            'name' => $form->name.' (copy)',
            'status' => Form::STATUS_DRAFT,
            'fields' => $form->fields,
            'settings' => $form->settings,
        ]);

        $this->dispatch('notify', message: 'Form duplicated.');
    }

    public function deleteForm(int $formId): void
    {
        auth()->user()->forms()->findOrFail($formId)->delete();

        $this->dispatch('notify', message: 'Form deleted.');
    }
};

?>

<div>
    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-extrabold tracking-tight">Forms</h1>
            <p class="mt-1 text-sm text-muted">Create, share and review your forms — all in one place.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('templates') }}" class="btn btn-outline">
                <x-icon name="template" class="size-4" />
                From template
            </a>
            <button wire:click="createForm" wire:loading.attr="disabled" class="btn btn-ink">
                <x-icon name="plus" class="size-4" />
                <span wire:loading.remove wire:target="createForm">New form</span>
                <span wire:loading wire:target="createForm">Creating…</span>
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mt-7 grid gap-4 sm:grid-cols-3">
        <div class="card rounded-2xl px-5 py-4">
            <p class="text-xs font-bold uppercase tracking-wide text-subtle">Forms</p>
            <p class="mt-1 font-display text-3xl font-extrabold">{{ number_format($this->stats['forms']) }}</p>
        </div>
        <div class="card rounded-2xl px-5 py-4">
            <p class="text-xs font-bold uppercase tracking-wide text-subtle">Total responses</p>
            <p class="mt-1 font-display text-3xl font-extrabold">{{ number_format($this->stats['responses']) }}</p>
        </div>
        <div class="card rounded-2xl px-5 py-4">
            <p class="text-xs font-bold uppercase tracking-wide text-subtle">This week</p>
            <p class="mt-1 font-display text-3xl font-extrabold">
                {{ number_format($this->stats['thisWeek']) }}
                @if ($this->stats['thisWeek'] > 0)
                    <span class="ml-1 align-middle text-xs font-semibold text-good">● live</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Search --}}
    @if ($this->stats['forms'] > 0)
        <div class="relative mt-8 max-w-xs">
            <x-icon name="search" class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-subtle" />
            <input type="search" wire:model.live.debounce.250ms="search" placeholder="Search forms…" class="input pl-9">
        </div>
    @endif

    {{-- Forms list --}}
    <div class="mt-4 space-y-2.5">
        @forelse ($this->forms as $form)
            <div class="card group relative flex items-center gap-4 rounded-2xl px-5 py-4 transition hover:border-line-strong" wire:key="form-{{ $form->id }}">
                <a href="{{ route('forms.edit', ['form' => $form]) }}" class="absolute inset-0 rounded-2xl" aria-label="Edit {{ $form->name }}"></a>

                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $form->isPublished() ? 'bg-accent-soft text-accent' : 'bg-ink-soft text-muted' }}">
                    <x-icon name="forms" class="size-5" />
                </span>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2.5">
                        <p class="truncate font-semibold">{{ $form->name }}</p>
                        @if ($form->isPublished())
                            <span class="chip border-good/30 bg-good-soft text-good">Live</span>
                        @elseif ($form->isClosed())
                            <span class="chip border-warn/30 bg-warn-soft text-warn">Closed</span>
                        @else
                            <span class="chip">Draft</span>
                        @endif
                    </div>
                    <p class="mt-0.5 truncate text-xs text-muted">
                        {{ count($form->fields ?? []) }} {{ \Illuminate\Support\Str::plural('question', count($form->fields ?? [])) }}
                        · Updated {{ $form->updated_at->diffForHumans() }}
                    </p>
                </div>

                <a href="{{ route('forms.responses', ['form' => $form]) }}" class="relative z-10 hidden items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-semibold text-muted transition hover:bg-ink-soft hover:text-ink sm:flex">
                    <x-icon name="inbox" class="size-4" />
                    {{ number_format($form->entries_count) }}
                </a>

                {{-- Row menu --}}
                <div x-data="{ open: false }" class="relative z-10">
                    <button x-on:click="open = !open" class="rounded-lg p-2 text-subtle transition hover:bg-ink-soft hover:text-ink" aria-label="Form actions">
                        <x-icon name="dots" class="size-4" />
                    </button>
                    <div
                        x-cloak
                        x-show="open"
                        x-on:click.outside="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 top-full z-20 mt-1 w-48 overflow-hidden rounded-xl border border-line bg-surface p-1.5 shadow-xl"
                    >
                        <a href="{{ route('forms.edit', ['form' => $form]) }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink">
                            <x-icon name="pencil" class="size-4" /> Edit
                        </a>
                        <a href="{{ route('forms.responses', ['form' => $form]) }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink">
                            <x-icon name="inbox" class="size-4" /> Responses
                        </a>
                        @if ($form->isPublished())
                            <button
                                x-on:click="navigator.clipboard.writeText('{{ $form->publicUrl() }}'); $dispatch('notify', { message: 'Link copied to clipboard.' }); open = false"
                                class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink"
                            >
                                <x-icon name="link" class="size-4" /> Copy link
                            </button>
                        @endif
                        <button wire:click="duplicateForm({{ $form->id }})" x-on:click="open = false" class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink">
                            <x-icon name="duplicate" class="size-4" /> Duplicate
                        </button>
                        <div class="my-1 border-t border-line"></div>
                        <button
                            wire:click="deleteForm({{ $form->id }})"
                            wire:confirm="Delete “{{ $form->name }}” and all {{ number_format($form->entries_count) }} of its responses? This cannot be undone."
                            x-on:click="open = false"
                            class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-bad transition hover:bg-bad-soft"
                        >
                            <x-icon name="trash" class="size-4" /> Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            @if (trim($search) !== '')
                <div class="card rounded-2xl px-6 py-14 text-center">
                    <p class="font-semibold">No forms match “{{ $search }}”</p>
                    <p class="mt-1 text-sm text-muted">Try a different search, or create a new form.</p>
                </div>
            @else
                <div class="card relative overflow-hidden rounded-2xl px-6 py-16 text-center">
                    <x-doodle name="sparkle" class="absolute left-[18%] top-10 size-9 -rotate-12 text-ink max-sm:hidden" />
                    <x-doodle name="bubble-heart" class="absolute right-[14%] top-12 size-12 rotate-6 text-ink max-sm:hidden" />
                    <x-doodle name="plane" class="mx-auto size-20 text-ink" />
                    <h2 class="mt-4 font-display text-2xl font-extrabold">Create your first form</h2>
                    <p class="mx-auto mt-2 max-w-sm text-sm text-muted">Start from a blank page or pick a template — either way, you'll be sharing a link in minutes.</p>
                    <div class="mt-6 flex items-center justify-center gap-2.5">
                        <a href="{{ route('templates') }}" class="btn btn-outline">Browse templates</a>
                        <button wire:click="createForm" class="btn btn-ink">
                            <x-icon name="plus" class="size-4" />
                            New form
                        </button>
                    </div>
                </div>
            @endif
        @endforelse
    </div>
</div>
