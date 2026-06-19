<?php

use App\Models\Form;
use App\Models\FormEntry;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

new class extends Component
{
    use WithPagination;

    #[Locked]
    public Form $form;

    public ?int $selectedId = null;

    public function mount(Form $form): void
    {
        $this->form = $form;
    }

    #[Computed]
    public function entries()
    {
        return $this->form->entries()->orderByDesc('created_at')->paginate(25);
    }

    /**
     * The first few questions become the table columns.
     *
     * @return list<array<string, mixed>>
     */
    #[Computed]
    public function columns(): array
    {
        return array_slice($this->form->inputFields(), 0, 4);
    }

    #[Computed]
    public function selected(): ?FormEntry
    {
        return $this->selectedId ? $this->form->entries()->find($this->selectedId) : null;
    }

    public function select(int $entryId): void
    {
        $entry = $this->form->entries()->findOrFail($entryId);

        if ($entry->read_at === null) {
            $entry->update(['read_at' => now()]);
        }

        $this->selectedId = $entryId;
    }

    public function closeDrawer(): void
    {
        $this->selectedId = null;
    }

    public function deleteEntry(int $entryId): void
    {
        $this->form->entries()->findOrFail($entryId)->delete();

        if ($this->selectedId === $entryId) {
            $this->selectedId = null;
        }

        unset($this->entries);
        $this->dispatch('notify', message: 'Response deleted.');
    }

    public function export(): StreamedResponse
    {
        $form = $this->form;
        $fields = $form->inputFields();
        $filename = Str::slug($form->name).'-responses.csv';

        return response()->streamDownload(function () use ($form, $fields) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Submitted at', ...array_map(fn ($f) => $f['label'] ?: $f['id'], $fields)]);

            $form->entries()->orderBy('created_at')->chunk(200, function ($entries) use ($handle, $fields) {
                foreach ($entries as $entry) {
                    fputcsv($handle, [
                        $entry->created_at->toDateTimeString(),
                        ...array_map(fn ($f) => $entry->answerFor($f['id']) ?? '', $fields),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
};

?>

<div>
    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-extrabold tracking-tight">Responses</h1>
            <p class="mt-1 text-sm text-muted">
                {{ number_format($this->form->entries()->count()) }} total
                @if ($latest = $this->form->entries()->latest()->first())
                    · last received {{ $latest->created_at->diffForHumans() }}
                @endif
            </p>
        </div>
        @if ($this->form->entries()->exists())
            <button wire:click="export" class="btn btn-outline">
                <x-icon name="download" class="size-4" />
                <span wire:loading.remove wire:target="export">Export CSV</span>
                <span wire:loading wire:target="export">Preparing…</span>
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="mt-7">
        @if ($this->entries->isEmpty())
            <div class="card relative overflow-hidden rounded-2xl px-6 py-16 text-center">
                <x-doodle name="squiggle" class="absolute left-[16%] top-12 size-10 text-accent max-sm:hidden" />
                <x-doodle name="loop" class="absolute right-[15%] top-10 size-11 text-ink max-sm:hidden" />
                <span class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-accent-soft text-accent">
                    <x-icon name="inbox" class="size-7" />
                </span>
                <h2 class="mt-5 font-display text-2xl font-extrabold">No responses yet</h2>
                <p class="mx-auto mt-2 max-w-sm text-sm text-muted">
                    @if ($this->form->isPublished())
                        Your form is live — share the link and responses will appear here in real time.
                    @else
                        Publish your form and share the link to start collecting responses.
                    @endif
                </p>
                <div class="mt-6">
                    @if ($this->form->isPublished())
                        <button
                            x-data
                            x-on:click="navigator.clipboard.writeText('{{ $this->form->publicUrl() }}'); $dispatch('notify', { message: 'Link copied to clipboard.' })"
                            class="btn btn-ink"
                        >
                            <x-icon name="link" class="size-4" /> Copy form link
                        </button>
                    @else
                        <a href="{{ route('forms.edit', ['form' => $this->form]) }}" class="btn btn-ink">Open the editor</a>
                    @endif
                </div>
            </div>
        @else
            <div class="card overflow-hidden rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-160 text-left text-sm">
                        <thead>
                            <tr class="border-b border-line bg-elevated/50 text-[11px] uppercase tracking-wide text-subtle">
                                @foreach ($this->columns as $column)
                                    <th class="px-5 py-3 font-bold">{{ \Illuminate\Support\Str::limit($column['label'] ?: 'Question', 28) }}</th>
                                @endforeach
                                <th class="px-5 py-3 text-right font-bold">Received</th>
                                <th class="w-10 px-3 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($this->entries as $entry)
                                <tr
                                    wire:key="entry-{{ $entry->id }}"
                                    wire:click="select({{ $entry->id }})"
                                    class="cursor-pointer transition hover:bg-canvas {{ $entry->read_at === null ? 'bg-accent-soft/40' : '' }}"
                                >
                                    @foreach ($this->columns as $i => $column)
                                        <td class="max-w-55 truncate px-5 py-3.5 {{ $i === 0 ? 'font-semibold' : 'text-muted' }}">
                                            @if ($entry->read_at === null && $i === 0)
                                                <span class="mr-1.5 inline-block size-1.5 -translate-y-px rounded-full bg-accent" title="Unread"></span>
                                            @endif
                                            {{ $entry->answerFor($column['id']) ?? '—' }}
                                        </td>
                                    @endforeach
                                    <td class="whitespace-nowrap px-5 py-3.5 text-right text-xs text-muted" title="{{ $entry->created_at->format('M j, Y · g:ia') }}">
                                        {{ $entry->created_at->diffForHumans(short: true) }}
                                    </td>
                                    <td class="px-3 py-3.5 text-right">
                                        <x-icon name="chevron-right" class="size-4 text-subtle" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                {{ $this->entries->links() }}
            </div>
        @endif
    </div>

    {{-- Detail drawer --}}
    @if ($this->selected)
        <div class="fixed inset-0 z-[70]" role="dialog" aria-modal="true" wire:key="drawer-{{ $this->selected->id }}">
            <div class="absolute inset-0 bg-ink/30 backdrop-blur-[2px]" wire:click="closeDrawer"></div>

            <aside
                class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col border-l border-line bg-surface shadow-2xl"
                x-data
                x-on:keydown.escape.window="$wire.closeDrawer()"
                x-init="$el.animate([{ transform: 'translateX(40px)', opacity: 0.6 }, { transform: 'translateX(0)', opacity: 1 }], { duration: 220, easing: 'cubic-bezier(0.2, 0.65, 0.25, 1)' })"
            >
                <header class="flex items-center justify-between border-b border-line px-6 py-4">
                    <div>
                        <p class="font-display text-lg font-extrabold">Response</p>
                        <p class="text-xs text-muted">{{ $this->selected->created_at->format('M j, Y · g:ia') }} · {{ $this->selected->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button
                            wire:click="deleteEntry({{ $this->selected->id }})"
                            wire:confirm="Delete this response? This cannot be undone."
                            class="rounded-lg p-2 text-subtle transition hover:bg-bad-soft hover:text-bad"
                            aria-label="Delete response"
                        >
                            <x-icon name="trash" class="size-4" />
                        </button>
                        <button wire:click="closeDrawer" class="rounded-lg p-2 text-subtle transition hover:bg-ink-soft hover:text-ink" aria-label="Close">
                            <x-icon name="x" class="size-4" />
                        </button>
                    </div>
                </header>

                <div class="flex-1 space-y-6 overflow-y-auto px-6 py-6">
                    @foreach ($this->form->inputFields() as $field)
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-subtle">{{ $field['label'] ?: 'Question' }}</p>
                            @php $answer = $this->selected->answerFor($field['id']); @endphp
                            @if ($answer === null)
                                <p class="mt-1 text-sm italic text-subtle">No answer</p>
                            @elseif ($field['type'] === 'rating')
                                <div class="mt-1.5 flex items-center gap-0.5 text-accent">
                                    @foreach (range(1, (int) ($field['max'] ?? 5)) as $star)
                                        <svg class="size-4" viewBox="0 0 24 24" fill="{{ $star <= (int) $answer ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.75"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                                    @endforeach
                                    <span class="ml-1.5 text-sm font-semibold text-ink">{{ $answer }}/{{ $field['max'] ?? 5 }}</span>
                                </div>
                            @elseif ($field['type'] === 'url')
                                <a href="{{ $answer }}" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 break-all text-sm font-medium text-accent underline decoration-accent-line underline-offset-2">
                                    {{ $answer }} <x-icon name="arrow-up-right" class="size-3 shrink-0" />
                                </a>
                            @elseif ($field['type'] === 'email')
                                <a href="mailto:{{ $answer }}" class="mt-1 inline-block text-sm font-medium text-accent underline decoration-accent-line underline-offset-2">{{ $answer }}</a>
                            @else
                                <p class="mt-1 whitespace-pre-line text-sm leading-relaxed">{{ $answer }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($this->selected->meta)
                    <footer class="border-t border-line bg-canvas px-6 py-3.5">
                        <p class="font-mono text-[11px] text-subtle">
                            {{ $this->selected->meta['ip'] ?? 'unknown ip' }}
                            @if (filled($this->selected->meta['referrer'] ?? null))
                                · via {{ parse_url($this->selected->meta['referrer'], PHP_URL_HOST) ?? $this->selected->meta['referrer'] }}
                            @endif
                        </p>
                    </footer>
                @endif
            </aside>
        </div>
    @endif
</div>
