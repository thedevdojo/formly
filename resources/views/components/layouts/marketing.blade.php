@props(['title' => null, 'description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-partials.head :title="$title" :description="$description" />
</head>
<body class="min-h-screen bg-canvas text-ink antialiased">

    {{-- Top navigation --}}
    <header
        x-data="{ scrolled: false, open: false }"
        x-on:scroll.window="scrolled = window.scrollY > 12"
        class="sticky top-0 z-50 transition-colors duration-300"
        :class="scrolled ? 'bg-canvas/85 backdrop-blur-md border-b border-line' : 'border-b border-transparent'"
    >
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="transition-opacity hover:opacity-80" aria-label="Formly home">
                    <x-logo />
                </a>
                <nav class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('templates') }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-muted transition hover:text-ink hover:bg-ink-soft">Templates</a>
                    <a href="{{ route('pricing') }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-muted transition hover:text-ink hover:bg-ink-soft">Pricing</a>
                    @if (\Devdojo\Foundation\Foundation::enabled('changelog'))
                        <a href="{{ url('/changelog') }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-muted transition hover:text-ink hover:bg-ink-soft">Changelog</a>
                    @endif
                </nav>
            </div>

            <div class="hidden items-center gap-2 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-ink btn-sm">
                        Go to dashboard
                        <x-icon name="arrow-right" class="size-3.5" />
                    </a>
                @else
                    <a href="{{ url('/auth/login') }}" class="btn btn-ghost btn-sm">Log in</a>
                    <a href="{{ url('/auth/register') }}" class="btn btn-ink btn-sm">Get started — it's free</a>
                @endauth
            </div>

            {{-- Mobile menu button --}}
            <button class="btn btn-ghost btn-sm md:hidden" x-on:click="open = !open" aria-label="Toggle menu">
                <x-icon name="dots" class="size-5" />
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-cloak x-show="open" x-transition.opacity class="border-b border-line bg-canvas px-5 pb-4 md:hidden">
            <nav class="flex flex-col gap-1 pt-2">
                <a href="{{ route('templates') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-ink-soft hover:text-ink">Templates</a>
                <a href="{{ route('pricing') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-ink-soft hover:text-ink">Pricing</a>
                @if (\Devdojo\Foundation\Foundation::enabled('changelog'))
                    <a href="{{ url('/changelog') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-ink-soft hover:text-ink">Changelog</a>
                @endif
                <div class="mt-2 flex flex-col gap-2 border-t border-line pt-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-ink">Go to dashboard</a>
                    @else
                        <a href="{{ url('/auth/login') }}" class="btn btn-outline">Log in</a>
                        <a href="{{ url('/auth/register') }}" class="btn btn-ink">Get started — it's free</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-panel text-panel-fg paper-grain">
        <div class="mx-auto max-w-6xl px-5 pt-16 pb-10">
            <div class="grid gap-12 md:grid-cols-[1.4fr_1fr_1fr_1fr]">
                <div>
                    <span class="inline-flex items-center gap-2.5">
                        <svg class="size-8" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                            <rect width="32" height="32" rx="9" fill="#f5f5f2"/>
                            <rect x="11" y="8" width="4.5" height="16" rx="2.25" fill="#121211"/>
                            <rect x="11" y="8" width="11.5" height="4.5" rx="2.25" fill="#121211"/>
                            <rect x="11" y="15" width="8.5" height="4.5" rx="2.25" fill="#ec1e9b"/>
                        </svg>
                        <span class="text-[1.3rem] font-extrabold tracking-tight leading-none">Formly</span>
                    </span>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-panel-muted">
                        The fastest way to build forms people actually finish. Write it like a doc, share a link, watch the answers arrive.
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-[0.14em] text-panel-muted">Product</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('templates') }}" class="text-panel-fg/80 transition hover:text-panel-fg">Templates</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-panel-fg/80 transition hover:text-panel-fg">Pricing</a></li>
                        @if (\Devdojo\Foundation\Foundation::enabled('changelog'))
                            <li><a href="{{ url('/changelog') }}" class="text-panel-fg/80 transition hover:text-panel-fg">Changelog</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-[0.14em] text-panel-muted">Company</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="#" class="text-panel-fg/80 transition hover:text-panel-fg">About</a></li>
                        <li><a href="#" class="text-panel-fg/80 transition hover:text-panel-fg">Blog</a></li>
                        <li><a href="#" class="text-panel-fg/80 transition hover:text-panel-fg">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-[0.14em] text-panel-muted">Legal</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="#" class="text-panel-fg/80 transition hover:text-panel-fg">Privacy</a></li>
                        <li><a href="#" class="text-panel-fg/80 transition hover:text-panel-fg">Terms</a></li>
                        <li><a href="#" class="text-panel-fg/80 transition hover:text-panel-fg">Security</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-14 flex flex-col items-start justify-between gap-4 border-t border-panel-line pt-6 text-xs text-panel-muted sm:flex-row sm:items-center">
                <p>© {{ date('Y') }} Formly. A DevDojo platform template.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="transition hover:text-panel-fg" aria-label="X (Twitter)"><x-icon name="x-social" class="size-4" /></a>
                    <a href="#" class="transition hover:text-panel-fg" aria-label="GitHub"><x-icon name="github" class="size-4" /></a>
                </div>
            </div>
        </div>
    </footer>

    <x-toasts />
</body>
</html>
