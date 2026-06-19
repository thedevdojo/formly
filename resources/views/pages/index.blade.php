<?php

use function Laravel\Folio\name;

name('home');

?>

<x-layouts.marketing>

    {{-- ============================== HERO ============================== --}}
    <section class="relative overflow-hidden">
        <div class="relative mx-auto max-w-6xl px-5 pb-12 pt-20 text-center sm:pt-28">

            {{-- Doodles --}}
            <x-doodle name="sparkle" class="reveal absolute left-[16%] top-10 size-11 -rotate-12 text-ink max-lg:hidden" style="animation-delay: 600ms" />
            <x-doodle name="plane" class="reveal absolute left-[4%] top-[58%] size-32 text-ink max-lg:hidden" style="animation-delay: 700ms" />
            <x-doodle name="squiggle" class="reveal absolute left-[27%] top-[72%] size-12 text-accent max-lg:hidden" style="animation-delay: 800ms" />
            <x-doodle name="bubble-heart" class="reveal absolute right-[7%] top-[44%] size-20 text-ink max-lg:hidden" style="animation-delay: 650ms" />
            <x-doodle name="sparkle" class="reveal absolute right-[3%] top-[36%] size-9 rotate-[100deg] text-ink max-lg:hidden" style="animation-delay: 750ms" />
            <x-doodle name="loop" class="reveal absolute bottom-6 right-[12%] size-12 text-ink max-lg:hidden" style="animation-delay: 850ms" />

            <h1 class="reveal mx-auto max-w-4xl text-5xl font-extrabold leading-[1.04] tracking-tight sm:text-7xl" style="animation-delay: 60ms">
                Forms that feel <span class="underline-squiggle">easy,</span><br>
                results that matter.
            </h1>

            <p class="reveal mx-auto mt-7 max-w-md text-lg leading-relaxed text-muted sm:text-xl" style="animation-delay: 160ms">
                Create beautiful forms in minutes. No code, no hassle, just better responses.
            </p>

            <div class="reveal mt-9" style="animation-delay: 240ms">
                <a href="{{ url('/auth/register') }}" class="btn btn-ink btn-lg group !px-7 !py-4 !text-base">
                    Create your first form — it's free
                    <x-icon name="arrow-up-right" class="size-4 text-accent transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </a>
                <p class="mt-4 text-sm text-muted">No credit card required</p>
            </div>
        </div>

        {{-- Browser-frame form preview --}}
        <div class="relative mx-auto max-w-6xl px-5 pb-24">
            <div class="reveal card mx-auto max-w-3xl overflow-hidden rounded-2xl !shadow-[0_24px_60px_-24px_rgba(17,17,16,0.18)]" style="animation-delay: 380ms">
                <div class="flex items-center gap-2 border-b border-line bg-surface px-5 py-3.5">
                    <span class="size-3 rounded-full bg-[#f4574d]"></span>
                    <span class="size-3 rounded-full bg-[#fbbc2e]"></span>
                    <span class="size-3 rounded-full bg-[#34b94f]"></span>
                </div>

                <div class="bg-surface px-8 pb-12 pt-10 sm:px-16">
                    <div class="text-center">
                        <p class="text-2xl font-extrabold tracking-tight sm:text-3xl">Event Feedback</p>
                        <p class="mt-1.5 text-sm text-muted">We'd love to hear your thoughts!</p>
                    </div>

                    <div class="mt-8 border-t border-line pt-8 text-left">
                        <div class="space-y-8">
                            {{-- Q1: interactive rating --}}
                            <div x-data="{ rating: 0, hover: 0 }">
                                <p class="text-sm font-bold">1. How would you rate the event? <span class="text-accent">*</span></p>
                                <div class="mt-2.5 flex gap-1">
                                    <template x-for="i in 5" :key="i">
                                        <button
                                            type="button"
                                            x-on:click="rating = i"
                                            x-on:mouseenter="hover = i"
                                            x-on:mouseleave="hover = 0"
                                            class="text-line-strong transition-transform hover:scale-110"
                                            :class="(hover ? i <= hover : i <= rating) && '!text-accent'"
                                            :aria-label="'Rate ' + i"
                                        >
                                            <svg class="size-7" viewBox="0 0 24 24" :fill="(hover ? i <= hover : i <= rating) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.6"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- Q2 --}}
                            <div x-data="{ value: '' }">
                                <p class="text-sm font-bold">2. What did you enjoy the most?</p>
                                <input type="text" x-model="value" placeholder="The talks, the people, the snacks…" class="input mt-2.5" maxlength="80">
                                <p class="mt-1.5 h-4 text-xs font-semibold text-accent" x-show="value.length > 2" x-cloak>Noted! ✨</p>
                            </div>

                            {{-- Q3 --}}
                            <div x-data="{ picked: null }">
                                <p class="text-sm font-bold">3. Would you attend again?</p>
                                <div class="mt-2.5 flex flex-wrap gap-2">
                                    @foreach (['Absolutely', 'Maybe', 'Probably not'] as $i => $option)
                                        <button
                                            type="button"
                                            x-on:click="picked = {{ $i }}"
                                            class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                                            :class="picked === {{ $i }} ? 'border-ink bg-ink text-canvas' : 'border-line-strong bg-surface text-muted hover:border-ink hover:text-ink'"
                                        >
                                            {{ $option }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-9">
                            <span class="btn btn-ink pointer-events-none !px-6">Submit feedback</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== SOCIAL PROOF ============================== --}}
    <section class="border-y border-line bg-surface">
        <div class="mx-auto max-w-6xl px-5 py-10" data-reveal>
            <p class="text-center text-xs font-extrabold uppercase tracking-[0.18em] text-subtle">Collecting answers for teams at</p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-x-10 gap-y-4 text-muted/70">
                <span class="text-lg font-extrabold tracking-tight">Northwind</span>
                <span class="text-lg font-extrabold tracking-tight">VANTA<span class="text-accent">POINT</span></span>
                <span class="font-mono text-base font-medium tracking-tight">apex.dev</span>
                <span class="text-lg font-bold tracking-[0.22em]">LUMEN</span>
                <span class="text-lg font-extrabold">Foster&nbsp;&amp;&nbsp;Co.</span>
                <span class="text-lg font-extrabold italic tracking-tight">brightside</span>
            </div>
        </div>
    </section>

    {{-- ============================== BUILD SECTION ============================== --}}
    <section class="mx-auto max-w-6xl px-5 py-24 sm:py-32">
        <div class="grid items-center gap-14 lg:grid-cols-2">
            <div data-reveal>
                <p class="flex items-center gap-2 text-sm font-extrabold text-accent"><x-icon name="pencil" class="size-4" /> Build</p>
                <h2 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">
                    Write it like a doc.<br>Not a <span class="underline-squiggle">flowchart.</span>
                </h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-muted">
                    No canvas, no drag-and-drop maze, no settings panels three layers deep. Your form is a document — click anywhere to type, hit <span class="kbd">/</span> to add a question, drag blocks to reorder.
                </p>
                <ul class="mt-8 space-y-4">
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-accent-soft text-accent"><x-icon name="check" class="size-3.5" /></span>
                        <p class="text-sm leading-relaxed"><strong class="font-bold">12 field types</strong> — from short answers and emails to star ratings, dropdowns and dates.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-accent-soft text-accent"><x-icon name="check" class="size-3.5" /></span>
                        <p class="text-sm leading-relaxed"><strong class="font-bold">Drag to reorder</strong> — grab any block by its handle and put it exactly where it belongs.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-accent-soft text-accent"><x-icon name="check" class="size-3.5" /></span>
                        <p class="text-sm leading-relaxed"><strong class="font-bold">Autosaves as you type</strong> — close the tab mid-thought, pick up where you left off.</p>
                    </li>
                </ul>
            </div>

            <div class="relative" data-reveal style="--reveal-delay: 120ms">
                <x-doodle name="sparkle" class="absolute -right-3 -top-7 size-10 rotate-45 text-accent max-sm:hidden" />
                <div class="card relative overflow-hidden rounded-2xl p-7 sm:p-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-subtle">The add-block menu</p>
                    <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                        @foreach (\App\Enums\FieldType::cases() as $type)
                            <div class="flex items-center gap-2.5 rounded-xl border border-line bg-canvas px-3 py-2.5 transition hover:border-accent-line hover:bg-accent-soft">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-surface text-muted shadow-sm ring-1 ring-line">
                                    <x-icon :name="$type->icon()" class="size-4" />
                                </span>
                                <span class="truncate text-[13px] font-bold">{{ $type->label() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== TRY IT SECTION ============================== --}}
    <section class="border-y border-line bg-surface">
        <div class="mx-auto max-w-6xl px-5 py-24 sm:py-32">
            <div class="relative mx-auto max-w-xl text-center" data-reveal>
                <x-doodle name="arrow-curve" class="absolute -left-24 top-2 size-16 -scale-x-100 text-ink max-lg:hidden" />
                <p class="flex items-center justify-center gap-2 text-sm font-extrabold text-accent"><x-icon name="sparkle" class="size-4" /> Try it</p>
                <h2 class="mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">Fields that feel <span class="underline-squiggle">alive.</span></h2>
                <p class="mt-4 text-base text-muted">Every field is keyboard-friendly, screen-reader-friendly and genuinely pleasant to use. Go on — click around.</p>
            </div>

            <div class="mx-auto mt-12 grid max-w-4xl gap-4 sm:grid-cols-2" data-reveal style="--reveal-delay: 100ms">
                {{-- Rating demo --}}
                <div class="card rounded-2xl border-line bg-canvas p-6" x-data="{ rating: 0, hover: 0 }">
                    <p class="text-sm font-bold">How's your day going?</p>
                    <div class="mt-3 flex gap-1">
                        <template x-for="i in 5" :key="i">
                            <button
                                x-on:click="rating = i"
                                x-on:mouseenter="hover = i"
                                x-on:mouseleave="hover = 0"
                                class="text-line-strong transition-transform hover:scale-110"
                                :class="(hover ? i <= hover : i <= rating) && '!text-accent'"
                                :aria-label="'Rate ' + i"
                            >
                                <svg class="size-7" viewBox="0 0 24 24" :fill="(hover ? i <= hover : i <= rating) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.75"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                            </button>
                        </template>
                    </div>
                    <p class="mt-2 h-4 text-xs font-bold text-accent" x-show="rating >= 4" x-cloak>Glad to hear it ✨</p>
                </div>

                {{-- Multiple choice demo --}}
                <div class="card rounded-2xl border-line bg-canvas p-6" x-data="{ picked: null }">
                    <p class="text-sm font-bold">Pick your superpower</p>
                    <div class="mt-3 space-y-2">
                        @foreach (['Flight', 'Invisibility', 'Inbox zero'] as $i => $option)
                            <button
                                x-on:click="picked = {{ $i }}"
                                class="flex w-full items-center gap-3 rounded-xl border px-3.5 py-2.5 text-left text-sm font-semibold transition"
                                :class="picked === {{ $i }} ? 'border-accent bg-accent-soft text-ink' : 'border-line bg-surface text-muted hover:border-line-strong'"
                            >
                                <span class="flex size-4 items-center justify-center rounded-full border" :class="picked === {{ $i }} ? 'border-accent' : 'border-line-strong'">
                                    <span class="size-2 rounded-full bg-accent transition" :class="picked === {{ $i }} ? 'scale-100' : 'scale-0'"></span>
                                </span>
                                {{ $option }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Short text demo --}}
                <div class="card rounded-2xl border-line bg-canvas p-6" x-data="{ value: '' }">
                    <p class="text-sm font-bold">What should we call you?</p>
                    <input type="text" x-model="value" placeholder="Type your name…" class="input mt-3" maxlength="40">
                    <p class="mt-2 h-4 text-xs font-semibold text-muted" x-show="value.length > 1" x-cloak>Nice to meet you, <span class="font-bold text-ink" x-text="value"></span> 👋</p>
                </div>

                {{-- Checkboxes demo --}}
                <div class="card rounded-2xl border-line bg-canvas p-6" x-data="{ checked: [] }">
                    <p class="text-sm font-bold">Toppings (choose any)</p>
                    <div class="mt-3 space-y-2">
                        @foreach (['Basil', 'Mushrooms', 'Pineapple — fight me'] as $i => $option)
                            <button
                                x-on:click="checked.includes({{ $i }}) ? checked = checked.filter(c => c !== {{ $i }}) : checked.push({{ $i }})"
                                class="flex w-full items-center gap-3 rounded-xl border px-3.5 py-2.5 text-left text-sm font-semibold transition"
                                :class="checked.includes({{ $i }}) ? 'border-accent bg-accent-soft text-ink' : 'border-line bg-surface text-muted hover:border-line-strong'"
                            >
                                <span class="flex size-4 items-center justify-center rounded border transition" :class="checked.includes({{ $i }}) ? 'border-accent bg-accent text-white' : 'border-line-strong bg-surface'">
                                    <svg class="size-3" x-show="checked.includes({{ $i }})" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg>
                                </span>
                                {{ $option }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== SHARE + RESPONSES ============================== --}}
    <section class="mx-auto max-w-6xl px-5 py-24 sm:py-32">
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Share card --}}
            <div class="card relative flex flex-col rounded-2xl p-8 sm:p-10" data-reveal>
                <x-doodle name="plane" class="absolute -top-9 right-8 size-20 text-ink max-sm:hidden" />
                <p class="flex items-center gap-2 text-sm font-extrabold text-accent"><x-icon name="share" class="size-4" /> Share</p>
                <h3 class="mt-3 text-3xl font-extrabold tracking-tight">One link. Anywhere.</h3>
                <p class="mt-3 text-sm leading-relaxed text-muted">Publish and you get a clean link that works in a tweet, an email, a QR code or embedded right into your site.</p>

                <div class="mt-8 space-y-3" x-data="{ copied: false }">
                    <div class="flex items-center gap-2 rounded-xl border border-line bg-canvas px-4 py-3">
                        <x-icon name="link" class="size-4 shrink-0 text-subtle" />
                        <span class="flex-1 truncate font-mono text-sm text-muted">formly.test/f/x4kQzR9mWp</span>
                        <button
                            x-on:click="copied = true; setTimeout(() => copied = false, 1600)"
                            class="btn btn-outline btn-sm relative"
                        >
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" x-cloak class="flex items-center gap-1 text-good"><x-icon name="check" class="size-3.5" /> Copied</span>
                        </button>
                    </div>
                    <div class="rounded-xl border border-line bg-panel p-4 font-mono text-xs leading-relaxed text-panel-muted">
                        <span class="text-[#8fb573]">&lt;iframe</span> <span class="text-[#c5a6e8]">src</span>=<span class="text-[#d9a05f]">"https://formly.test/f/x4kQzR9mWp"</span><br>
                        &nbsp;&nbsp;<span class="text-[#c5a6e8]">width</span>=<span class="text-[#d9a05f]">"100%"</span> <span class="text-[#c5a6e8]">height</span>=<span class="text-[#d9a05f]">"600"</span><span class="text-[#8fb573]">&gt;&lt;/iframe&gt;</span>
                    </div>
                </div>
            </div>

            {{-- Responses card --}}
            <div class="card flex flex-col rounded-2xl p-8 sm:p-10" data-reveal style="--reveal-delay: 120ms">
                <p class="flex items-center gap-2 text-sm font-extrabold text-accent"><x-icon name="inbox" class="size-4" /> Responses</p>
                <h3 class="mt-3 text-3xl font-extrabold tracking-tight">Answers, <span class="underline-squiggle">organized.</span></h3>
                <p class="mt-3 text-sm leading-relaxed text-muted">Every submission lands in a tidy inbox — scan it like a spreadsheet, open the detail view, or export the lot to CSV.</p>

                <div class="mt-8 overflow-hidden rounded-xl border border-line">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-line bg-elevated/60 text-[11px] uppercase tracking-wide text-subtle">
                                <th class="px-3.5 py-2.5 font-extrabold">Name</th>
                                <th class="px-3.5 py-2.5 font-extrabold">Ticket</th>
                                <th class="px-3.5 py-2.5 font-extrabold">Received</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line bg-surface">
                            <tr><td class="px-3.5 py-2.5 font-bold">Maya Chen</td><td class="px-3.5 py-2.5"><span class="rounded-full bg-accent-soft px-2 py-0.5 font-bold text-accent">VIP</span></td><td class="px-3.5 py-2.5 text-muted">2m ago</td></tr>
                            <tr><td class="px-3.5 py-2.5 font-bold">Tom Okafor</td><td class="px-3.5 py-2.5"><span class="rounded-full bg-ink-soft px-2 py-0.5 font-bold text-muted">General</span></td><td class="px-3.5 py-2.5 text-muted">11m ago</td></tr>
                            <tr><td class="px-3.5 py-2.5 font-bold">Ana Sousa</td><td class="px-3.5 py-2.5"><span class="rounded-full bg-ink-soft px-2 py-0.5 font-bold text-muted">Online</span></td><td class="px-3.5 py-2.5 text-muted">34m ago</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-muted">
                    <span class="flex items-center gap-1.5"><span class="size-1.5 animate-pulse rounded-full bg-good"></span> 31 responses this week</span>
                    <span class="flex items-center gap-1.5 font-bold"><x-icon name="download" class="size-3.5" /> Export CSV</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== TESTIMONIALS ============================== --}}
    <section class="border-t border-line bg-surface">
        <div class="mx-auto max-w-6xl px-5 py-24 sm:py-32">
            <div class="relative mx-auto max-w-xl text-center" data-reveal>
                <x-doodle name="bubble-heart" class="absolute -right-24 -top-6 size-16 rotate-6 text-ink max-lg:hidden" />
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl">People <span class="underline-squiggle">notice.</span></h2>
            </div>
            <div class="mt-12 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['quote' => 'Our application form completion rate went from 54% to 87% the week we switched. The forms just feel… lighter.', 'name' => 'Priya Raman', 'title' => 'Head of Talent, Vantapoint'],
                    ['quote' => 'I built our entire event registration during one coffee. Sent the link before the cup was cold.', 'name' => 'Daniel Kim', 'title' => 'Founder, apex.dev'],
                    ['quote' => 'The response inbox is the feature I didn\'t know I needed. No more exporting to spreadsheets just to read answers.', 'name' => 'Sofia Marchetti', 'title' => 'Research Lead, Lumen'],
                ] as $i => $t)
                    <figure class="card rounded-2xl border-line bg-canvas p-7" data-reveal style="--reveal-delay: {{ $i * 110 }}ms">
                        <div class="flex gap-0.5 text-accent" aria-hidden="true">
                            @foreach (range(1, 5) as $s)
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.6l1-5.8-4.3-4.1 5.9-.9L12 3Z"/></svg>
                            @endforeach
                        </div>
                        <blockquote class="mt-4 text-[15px] font-semibold leading-relaxed">"{{ $t['quote'] }}"</blockquote>
                        <figcaption class="mt-5 text-sm">
                            <span class="font-bold">{{ $t['name'] }}</span>
                            <span class="block text-xs text-muted">{{ $t['title'] }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================== CTA BAND ============================== --}}
    <section class="bg-panel text-panel-fg">
        <div class="relative mx-auto max-w-6xl overflow-hidden px-5 py-24 text-center sm:py-32" data-reveal>
            <x-doodle name="plane" class="absolute left-[6%] top-12 size-24 text-panel-muted max-lg:hidden" />
            <x-doodle name="sparkle" class="absolute right-[10%] top-14 size-10 rotate-45 text-accent max-lg:hidden" />
            <x-doodle name="loop" class="absolute bottom-10 right-[6%] size-14 text-panel-muted max-lg:hidden" />

            <h2 class="mx-auto max-w-2xl text-4xl font-extrabold leading-tight tracking-tight sm:text-6xl">
                Your first form is<br><span class="underline-squiggle">60 seconds</span> away.
            </h2>
            <p class="mx-auto mt-6 max-w-md text-base text-panel-muted">Free to start. Unlimited forms. Your first 100 responses each month are on us.</p>
            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ url('/auth/register') }}" class="btn btn-primary btn-lg w-full sm:w-auto">
                    Create your free account
                    <x-icon name="arrow-up-right" class="size-4" />
                </a>
                <a href="{{ route('pricing') }}" class="btn btn-lg w-full border-panel-line text-panel-fg hover:bg-panel-elevated sm:w-auto">See pricing</a>
            </div>
        </div>
    </section>

</x-layouts.marketing>
