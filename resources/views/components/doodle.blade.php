@props(['name' => 'sparkle'])

@php
    // Hand-drawn doodle accents. Stroke-based, slightly irregular paths,
    // round caps — colored via text-* utilities (currentColor).
    $doodles = [
        // Three radiating "ta-da" strokes.
        'sparkle' => [
            'viewBox' => '0 0 48 48',
            'paths' => '<path d="M10 38C14 31 17 26 21 21" stroke-width="3"/><path d="M22 40c1.5-5 2.6-9 4.5-14" stroke-width="3"/><path d="M5 28c4.5-2.5 8-4.5 12-7.5" stroke-width="3"/>',
        ],
        // Paper plane with a dashed looping trail.
        'plane' => [
            'viewBox' => '0 0 120 90',
            'paths' => '<path d="M72 14 116 4 92 40l-9-15-12-11Z" stroke-width="3" stroke-linejoin="round"/><path d="m83 25 16-16M83 25l9 15" stroke-width="3"/><path d="M68 30C52 44 44 42 36 38c-9-4.5-16 1-15 8 1 6 8 9 14 5 7-4.6.5-14-8-13-9.5 1.2-17 7-23 14" stroke-width="3" stroke-dasharray="7 7"/>',
        ],
        // Speech bubble with a heart inside.
        'bubble-heart' => [
            'viewBox' => '0 0 96 96',
            'paths' => '<path d="M48 12c19 0 33 12.5 33 29 0 16.5-14 29.5-33 29.5-3.6 0-7-.4-10.2-1.3C32 73.5 26 78.5 19 81c2.6-5 3.6-9.5 3-13.5C16.5 62.3 14 52.8 15 41c1.6-17 14-29 33-29Z" stroke-width="3" stroke-linejoin="round"/><path class="doodle-accent" d="M48 56 37.5 45.5c-3-3-2.6-8 .8-10.4 3-2.2 7-1.4 9.7 1.6 2.7-3 6.7-3.8 9.7-1.6 3.4 2.4 3.8 7.4.8 10.4L48 56Z" stroke-width="3" stroke-linejoin="round"/>',
        ],
        // Short horizontal pink squiggle.
        'squiggle' => [
            'viewBox' => '0 0 64 16',
            'paths' => '<path d="M3 11c5-6 9-6 13-1.5S25 14 30 8.5 40 3 45 8s9 4.5 16-1" stroke-width="3.5"/>',
        ],
        // Little spiral loop scribble.
        'loop' => [
            'viewBox' => '0 0 56 40',
            'paths' => '<path d="M5 31c6-12 14-21 21-19 6 1.7 4 10-1.5 11C19 24 17 17 22 12c6.5-6.5 19-5 29 6" stroke-width="3"/>',
        ],
        // Hand-drawn curved arrow pointing down-right.
        'arrow-curve' => [
            'viewBox' => '0 0 72 72',
            'paths' => '<path d="M10 8c4 26 18 42 46 48" stroke-width="3"/><path d="M44 60.5c5 .5 8.5-.5 12-4.5M56 56c-1-5-3.5-8-7.5-10.5" stroke-width="3"/>',
        ],
        // Tiny asterisk/star burst.
        'burst' => [
            'viewBox' => '0 0 40 40',
            'paths' => '<path d="M20 6v28M8 13l24 14M32 13 8 27" stroke-width="3.2"/>',
        ],
        // Wavy check / tick flourish.
        'check-flourish' => [
            'viewBox' => '0 0 64 48',
            'paths' => '<path d="M8 26c6 4 10 9 13 15C27 25 39 13 56 6" stroke-width="4"/>',
        ],
    ];

    $doodle = $doodles[$name] ?? $doodles['sparkle'];
@endphp

<svg {{ $attributes->merge(['class' => 'size-10']) }} viewBox="{{ $doodle['viewBox'] }}" fill="none" stroke="currentColor" stroke-linecap="round" aria-hidden="true">
    {!! $doodle['paths'] !!}
</svg>
