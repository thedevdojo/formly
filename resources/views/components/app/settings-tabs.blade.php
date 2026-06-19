@php
    $tabs = [
        ['label' => 'Account', 'route' => 'settings.account', 'icon' => 'user'],
        ['label' => 'Security', 'route' => 'settings.security', 'icon' => 'lock'],
    ];

    if (\Devdojo\Foundation\Foundation::enabled('billing')) {
        $tabs[] = ['label' => 'Billing', 'route' => 'settings.billing', 'icon' => 'credit-card'];
    }
@endphp

<div>
    <h1 class="font-display text-3xl font-extrabold tracking-tight">Settings</h1>
    <nav class="mt-6 flex items-center gap-1 border-b border-line">
        @foreach ($tabs as $tab)
            <a
                href="{{ route($tab['route']) }}"
                class="relative flex items-center gap-1.5 px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs($tab['route']) ? 'text-ink' : 'text-muted hover:text-ink' }}"
            >
                <x-icon :name="$tab['icon']" class="size-3.5" />
                {{ $tab['label'] }}
                @if (request()->routeIs($tab['route']))
                    <span class="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-ink"></span>
                @endif
            </a>
        @endforeach
    </nav>
</div>
