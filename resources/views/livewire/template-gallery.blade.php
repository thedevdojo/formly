<?php

use App\Support\FormTemplates;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    /**
     * @return array<string, array<string, mixed>>
     */
    #[Computed]
    public function templates(): array
    {
        return FormTemplates::all();
    }

    public function useTemplate(string $key)
    {
        if (! auth()->check()) {
            return $this->redirect(url('/auth/register'));
        }

        $form = FormTemplates::createForUser($key, auth()->id());

        if ($form === null) {
            $this->dispatch('notify', message: 'Template not found.', type: 'error');

            return;
        }

        return $this->redirect(route('forms.edit', ['form' => $form]));
    }
};

?>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($this->templates as $key => $template)
        <div class="card group flex flex-col overflow-hidden rounded-2xl transition duration-300 hover:-translate-y-1 hover:shadow-lg" wire:key="template-{{ $key }}">

            {{-- Mini preview --}}
            <div class="relative border-b border-line bg-canvas-deep/70 px-6 pb-0 pt-6">
                <div class="rounded-t-xl border border-b-0 border-line bg-surface px-5 pb-4 pt-5 shadow-sm">
                    <p class="font-display text-sm font-extrabold tracking-tight">{{ $template['name'] }}</p>
                    <div class="mt-3 space-y-2.5">
                        @foreach (array_slice($template['fields'], 0, 3) as $field)
                            <div>
                                <div class="h-1.5 rounded-full bg-ink-soft" style="width: {{ 35 + (strlen($field['label']) * 7) % 40 }}%"></div>
                                @if (in_array($field['type'], ['multiple_choice', 'checkboxes', 'select']))
                                    <div class="mt-1.5 flex gap-1">
                                        @foreach (array_slice($field['options'] ?? [], 0, 3) as $option)
                                            <div class="h-3.5 rounded-full border border-line bg-canvas px-2"></div>
                                        @endforeach
                                    </div>
                                @elseif ($field['type'] === 'rating')
                                    <div class="mt-1.5 flex gap-0.5 text-accent">
                                        @foreach (range(1, 5) as $s)
                                            <svg class="size-2.5" viewBox="0 0 24 24" fill="{{ $s <= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                                        @endforeach
                                    </div>
                                @elseif ($field['type'] === 'long_text')
                                    <div class="mt-1.5 h-7 rounded-md border border-line bg-canvas"></div>
                                @else
                                    <div class="mt-1.5 h-4 rounded-md border border-line bg-canvas"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Meta + action --}}
            <div class="flex flex-1 flex-col p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-accent-soft text-accent">
                            <x-icon :name="$template['icon']" class="size-4" />
                        </span>
                        <div>
                            <p class="text-sm font-bold">{{ $template['name'] }}</p>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-subtle">{{ $template['category'] }}</p>
                        </div>
                    </div>
                </div>
                <p class="mt-3 flex-1 text-[13px] leading-relaxed text-muted">{{ $template['description'] }}</p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xs text-subtle">{{ count($template['fields']) }} {{ \Illuminate\Support\Str::plural('question', count($template['fields'])) }}</span>
                    <button
                        wire:click="useTemplate('{{ $key }}')"
                        wire:loading.attr="disabled"
                        class="btn btn-outline btn-sm group-hover:border-ink"
                    >
                        <span wire:loading.remove wire:target="useTemplate('{{ $key }}')">Use template</span>
                        <span wire:loading wire:target="useTemplate('{{ $key }}')">Creating…</span>
                        <x-icon name="arrow-right" class="size-3.5" />
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
