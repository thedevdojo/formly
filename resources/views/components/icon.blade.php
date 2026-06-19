@props(['name' => 'circle'])

@php
    // All icons share a 24px stroke-based grid. Filled details set their own fill.
    $icons = [
        // ---- Field types ----
        'text' => '<path d="M4 7V5h16v2M12 5v14M9 19h6"/>',
        'paragraph' => '<path d="M4 6h16M4 11h16M4 16h10"/>',
        'at' => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a2.5 2.5 0 0 0 5 0v-1a9 9 0 1 0-3.5 7.1"/>',
        'phone' => '<path d="M5 4h4l1.5 4.5-2.3 1.6a12.5 12.5 0 0 0 5.7 5.7l1.6-2.3L20 15v4a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 3 6.2 2 2 0 0 1 5 4Z"/>',
        'link' => '<path d="M9 15l6-6"/><path d="M11 6.5 12.5 5a4 4 0 0 1 5.6 5.6L16.5 12"/><path d="M13 17.5 11.5 19a4 4 0 0 1-5.6-5.6L7.5 12"/>',
        'hash' => '<path d="M9 4 7 20M17 4l-2 16M4 9h17M3 15h17"/>',
        'chevron-down-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 10.5 3.5 3.5 3.5-3.5"/>',
        'radio' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3.5" fill="currentColor" stroke="none"/>',
        'checkbox' => '<rect x="3.5" y="3.5" width="17" height="17" rx="4"/><path d="m8.5 12 2.4 2.4L15.5 9.5"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="16" rx="2.5"/><path d="M3.5 9.5h17M8 3v4M16 3v4"/>',
        'star' => '<path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/>',
        'heading' => '<path d="M5 5v14M15 5v14M5 12h10M19 13.5V19M19 13.5c0-1 .8-1.8 1.8-1.8"/>',

        // ---- App chrome ----
        'forms' => '<rect x="4" y="3" width="16" height="18" rx="2.5"/><path d="M8 8h8M8 12h5.5M8 16h3"/>',
        'inbox' => '<path d="M4 13l2.5-7.5A2 2 0 0 1 8.4 4h7.2a2 2 0 0 1 1.9 1.5L20 13"/><path d="M4 13v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5h-5a3 3 0 0 1-6 0H4Z"/>',
        'bell' => '<path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 13.5a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-2.9 1.2v.2a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0-1.2-2.9H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1.1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H10a1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V10a1.7 1.7 0 0 0 1.6 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.2-3.2"/>',
        'megaphone' => '<path d="M4 10v4a1 1 0 0 0 1 1h2l9 4V5L7 9H5a1 1 0 0 0-1 1Z"/><path d="M16 8a4 4 0 0 1 0 8"/>',
        'credit-card' => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3 10h18M7 15h3"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/>',
        'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M16 5.2a3.5 3.5 0 0 1 0 6.6M21 20a6 6 0 0 0-4-5.7"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/>',
        'template' => '<rect x="3" y="3" width="18" height="6" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="15" y="13" width="6" height="8" rx="1.5"/>',
        'chart' => '<path d="M4 20V10M10 20V4M16 20v-7M21 20H3"/>',

        // ---- Actions ----
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'x' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'check' => '<path d="m5 12 5 5L20 7"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.3 2.3L16 9"/>',
        'trash' => '<path d="M4 7h16M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12"/>',
        'copy' => '<rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>',
        'duplicate' => '<rect x="8" y="8" width="13" height="13" rx="2.5"/><path d="M16 3.5H6A2.5 2.5 0 0 0 3.5 6v10"/><path d="M14.5 12v5M12 14.5h5"/>',
        'grip' => '<circle cx="9" cy="6" r="1.3" fill="currentColor" stroke="none"/><circle cx="15" cy="6" r="1.3" fill="currentColor" stroke="none"/><circle cx="9" cy="12" r="1.3" fill="currentColor" stroke="none"/><circle cx="15" cy="12" r="1.3" fill="currentColor" stroke="none"/><circle cx="9" cy="18" r="1.3" fill="currentColor" stroke="none"/><circle cx="15" cy="18" r="1.3" fill="currentColor" stroke="none"/>',
        'dots' => '<circle cx="5" cy="12" r="1.4" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/><circle cx="19" cy="12" r="1.4" fill="currentColor" stroke="none"/>',
        'dots-vertical' => '<circle cx="12" cy="5" r="1.4" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/><circle cx="12" cy="19" r="1.4" fill="currentColor" stroke="none"/>',
        'pencil' => '<path d="M4 20h4L18.5 9.5a2.1 2.1 0 0 0-3-3L5 17v3Z"/><path d="m14 7 3 3"/>',
        'eye' => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/>',
        'download' => '<path d="M12 4v11M7 11l5 5 5-5"/><path d="M4 20h16"/>',
        'share' => '<circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="18" cy="18" r="2.5"/><path d="m8.3 10.8 7.4-3.6M8.3 13.2l7.4 3.6"/>',
        'send' => '<path d="M21 3 3 10.5l7 2.5M21 3l-6 18-4.5-8M21 3 10 13"/>',
        'code' => '<path d="m8 7-5 5 5 5M16 7l5 5-5 5"/>',
        'refresh' => '<path d="M20 8A8 8 0 0 0 6.3 6.3L4 8.5M4 16a8 8 0 0 0 13.7 1.7L20 15.5"/><path d="M4 4v4.5h4.5M20 20v-4.5h-4.5"/>',

        // ---- Arrows & chevrons ----
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-left' => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
        'arrow-up-right' => '<path d="M7 17 17 7M7 7h10v10"/>',
        'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
        'chevron-up' => '<path d="m6 15 6-6 6 6"/>',
        'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
        'chevron-left' => '<path d="m15 6-6 6 6 6"/>',
        'chevrons-up-down' => '<path d="m7 15 5 5 5-5M7 9l5-5 5 5"/>',

        // ---- States & misc ----
        'sparkle' => '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/>',
        'sparkles' => '<path d="M12 4l1.4 4L17.5 9.5 13.4 11 12 15l-1.4-4L6.5 9.5 10.6 8 12 4Z"/><path d="M18 14l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7.7-2Z" fill="currentColor" stroke="none"/>',
        'zap' => '<path d="M13 3 4 14h7l-1 7 9-11h-7l1-7Z"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.6 3.8 9S14.5 18.5 12 21c-2.5-2.5-3.8-5.6-3.8-9S9.5 5.5 12 3Z"/>',
        'lock' => '<rect x="4.5" y="10" width="15" height="10" rx="2.5"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        'shield' => '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z"/><path d="m9.5 12 1.8 1.8L15 10"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m4 7 8 6 8-6"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 2"/>',
        'alert' => '<path d="M12 3 2.5 20h19L12 3Z"/><path d="M12 10v4M12 17.5h.01"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
        'heart' => '<path d="M12 20.5 4.7 13a4.8 4.8 0 0 1 0-6.8 4.7 4.7 0 0 1 6.7 0l.6.6.6-.6a4.7 4.7 0 0 1 6.7 0 4.8 4.8 0 0 1 0 6.8L12 20.5Z"/>',
        'message' => '<path d="M21 11.5a8.5 8.5 0 0 1-12.3 7.6L3 21l1.9-5.7A8.5 8.5 0 1 1 21 11.5Z"/>',
        'file-text' => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path d="M14 3v5h5M9 13h6M9 17h4"/>',
        'rocket' => '<path d="M5 15c-1.5 1-2 5-2 5s4-.5 5-2c.6-.9.5-2-.3-2.7-.8-.8-1.9-.9-2.7-.3Z"/><path d="M9.5 14.5 7 12a13 13 0 0 1 9-9c1.7 0 3 1.3 3 3a13 13 0 0 1-9 9Z"/><circle cx="14.5" cy="8.5" r="1.5"/>',
        'wand' => '<path d="m14 7 3 3M5 19l9-9M19 5l.9-.9M17.5 9.5 19 8M14.5 6.5 16 5M20.5 12.5 22 11"/>',
        'circle' => '<circle cx="12" cy="12" r="9"/>',
        'github' => '<path d="M9 19c-4.3 1.4-4.3-2.5-6-3m12 5v-3.5c0-1 .1-1.4-.5-2 2.8-.3 5.5-1.4 5.5-6a4.6 4.6 0 0 0-1.3-3.2 4.2 4.2 0 0 0-.1-3.2s-1.1-.3-3.5 1.3a12 12 0 0 0-6 0C6.2 3.1 5.1 3.4 5.1 3.4a4.2 4.2 0 0 0-.1 3.2A4.6 4.6 0 0 0 3.6 9.8c0 4.6 2.7 5.7 5.5 6-.6.6-.6 1.2-.5 2V21"/>',
        'x-social' => '<path d="M4 4l16 16M20 4 4 20" stroke-width="2"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'size-4']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $icons[$name] ?? $icons['circle'] !!}
</svg>
