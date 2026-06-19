@props(['class' => ''])

<span {{ $attributes->merge(['class' => 'relative inline-flex items-center gap-2.5 '.$class]) }}>
    {{-- hand-drawn crown doodle over the mark --}}
    <svg class="absolute -left-1 -top-2.5 size-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M4 16c.6-3 1.2-5.5 1.5-8 1.4 1.8 2.6 3 3.8 3.6C10.4 9.3 11.3 7 12 5c.8 2 1.7 4.2 2.8 6.4 1.3-.7 2.5-1.9 3.7-3.5.4 2.6.9 5.2 1.5 8"/>
    </svg>
    <x-logo-icon class="size-8" />
    <span class="text-[1.3rem] font-extrabold tracking-tight leading-none">Formly</span>
</span>
