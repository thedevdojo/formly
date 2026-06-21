@props(['class' => ''])

<span {{ $attributes->merge(['class' => 'relative inline-flex items-center gap-2.5 '.$class]) }}>
    
    <x-logo-icon class="size-7" />
    <span class="text-[1.3rem] font-extrabold tracking-tight leading-none">Formly</span>
</span>
