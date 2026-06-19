@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-partials.head :title="$title" />
</head>
<body class="min-h-screen bg-canvas text-ink antialiased">

    <header class="sticky top-0 z-40 border-b border-line bg-canvas/90 backdrop-blur-md">
        <div class="mx-auto flex h-14 max-w-6xl items-center justify-between gap-4 px-5">
            <div class="flex min-w-0 items-center gap-5">
                <a href="{{ route('dashboard') }}" class="shrink-0 transition-opacity hover:opacity-80" aria-label="Dashboard">
                    <x-logo-icon class="size-7" />
                </a>

                @isset($breadcrumb)
                    <div class="flex min-w-0 items-center gap-2 text-sm">
                        {{ $breadcrumb }}
                    </div>
                @else
                    <nav class="flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-ink-soft text-ink' : 'text-muted hover:text-ink hover:bg-ink-soft' }}">Forms</a>
                        <a href="{{ route('templates') }}" class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ request()->routeIs('templates') ? 'bg-ink-soft text-ink' : 'text-muted hover:text-ink hover:bg-ink-soft' }}">Templates</a>
                    </nav>
                @endisset
            </div>

            <div class="flex shrink-0 items-center gap-1.5">
                {{ $actions ?? '' }}

                @if (\Devdojo\Foundation\Foundation::enabled('changelog'))
                    <a href="{{ route('changelog.index') }}" class="relative rounded-lg p-2 text-muted transition hover:bg-ink-soft hover:text-ink" title="What's new" aria-label="What's new">
                        <x-icon name="megaphone" class="size-[18px]" />
                        @if (auth()->user()->hasChangelogNotifications())
                            <span class="absolute right-1.5 top-1.5 size-2 rounded-full bg-accent ring-2 ring-canvas"></span>
                        @endif
                    </a>
                @endif

                <div x-data="{ open: false }" class="relative">
                    <button x-on:click="open = !open" class="flex items-center rounded-full transition hover:opacity-85" aria-label="Account menu">
                        <x-avatar :user="auth()->user()" class="size-8" />
                    </button>

                    <div
                        x-cloak
                        x-show="open"
                        x-on:click.outside="open = false"
                        x-on:keydown.escape.window="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute right-0 top-full z-50 mt-2 w-60 origin-top-right overflow-hidden rounded-xl border border-line bg-surface shadow-xl"
                    >
                        <div class="border-b border-line px-4 py-3">
                            <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-muted">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-1.5">
                            <a href="{{ route('settings.account') }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink">
                                <x-icon name="settings" class="size-4" /> Settings
                            </a>
                            @if (\Devdojo\Foundation\Foundation::enabled('billing'))
                                <a href="{{ route('settings.billing') }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink">
                                    <x-icon name="credit-card" class="size-4" /> Billing & plan
                                </a>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <a href="{{ url('/foundation/setup') }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-ink-soft hover:text-ink">
                                    <x-icon name="zap" class="size-4" /> Platform features
                                </a>
                            @endif
                        </div>
                        <div class="border-t border-line p-1.5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm font-medium text-muted transition hover:bg-bad-soft hover:text-bad">
                                    <x-icon name="logout" class="size-4" /> Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @isset($subnav)
            <div class="border-t border-line bg-canvas">
                <div class="mx-auto max-w-6xl px-5">
                    {{ $subnav }}
                </div>
            </div>
        @endisset
    </header>

    <main {{ $attributes->merge(['class' => 'mx-auto max-w-6xl px-5 py-8']) }}>
        {{ $slot }}
    </main>

    <x-toasts />
</body>
</html>
