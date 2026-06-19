@props(['class' => 'size-8'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <rect width="32" height="32" rx="9" fill="var(--ink)"/>
    <rect x="11" y="8" width="4.5" height="16" rx="2.25" fill="var(--canvas)"/>
    <rect x="11" y="8" width="11.5" height="4.5" rx="2.25" fill="var(--canvas)"/>
    <rect x="11" y="15" width="8.5" height="4.5" rx="2.25" fill="var(--accent)"/>
</svg>
