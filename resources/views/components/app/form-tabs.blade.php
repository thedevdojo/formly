@props(['form', 'active' => 'edit'])

@php
    $tabs = [
        'edit' => ['label' => 'Edit', 'icon' => 'pencil', 'href' => route('forms.edit', ['form' => $form])],
        'responses' => ['label' => 'Responses', 'icon' => 'inbox', 'href' => route('forms.responses', ['form' => $form])],
        'settings' => ['label' => 'Settings', 'icon' => 'settings', 'href' => route('forms.settings', ['form' => $form])],
    ];
@endphp

<nav class="-mb-px flex items-center gap-1">
    @foreach ($tabs as $key => $tab)
        <a
            href="{{ $tab['href'] }}"
            class="relative flex items-center gap-1.5 px-3 py-2.5 text-sm font-semibold transition {{ $active === $key ? 'text-ink' : 'text-muted hover:text-ink' }}"
        >
            <x-icon :name="$tab['icon']" class="size-3.5" />
            {{ $tab['label'] }}
            @if ($key === 'responses' && $form->entries_count ?? false)
                <span class="rounded-full bg-ink-soft px-1.5 py-0.5 text-[11px] font-bold text-muted">{{ number_format($form->entries_count) }}</span>
            @endif
            @if ($active === $key)
                <span class="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-ink"></span>
            @endif
        </a>
    @endforeach
</nav>
