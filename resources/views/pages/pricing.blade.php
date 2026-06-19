<?php

use Devdojo\Billing\Models\Plan;

use function Laravel\Folio\name;

name('pricing');

?>

@php
    $plans = Plan::where('active', true)->orderBy('sort_order')->get();
@endphp

<x-layouts.marketing title="Pricing" description="Simple, honest pricing. Free for your first 100 responses a month — upgrade when you grow.">

    <section class="relative overflow-hidden">
        <div class="relative mx-auto max-w-6xl px-5 pb-24 pt-16 sm:pt-24" x-data="{ yearly: true }">
            <x-doodle name="sparkle" class="reveal absolute left-[14%] top-14 size-10 -rotate-12 text-ink max-lg:hidden" style="animation-delay: 500ms" />
            <x-doodle name="bubble-heart" class="reveal absolute right-[10%] top-24 size-16 rotate-6 text-ink max-lg:hidden" style="animation-delay: 600ms" />
            <div class="mx-auto max-w-2xl text-center">
                <p class="reveal flex items-center justify-center gap-2 text-sm font-bold text-accent" style="animation-delay: 0ms">
                    <x-icon name="credit-card" class="size-4" /> Pricing
                </p>
                <h1 class="reveal mt-3 font-display text-5xl font-extrabold tracking-tight sm:text-6xl" style="animation-delay: 80ms">
                    Simple. <span class="underline-squiggle">Honest.</span>
                </h1>
                <p class="reveal mx-auto mt-5 max-w-md text-lg text-muted" style="animation-delay: 160ms">
                    Start free, stay free for your first 100 responses every month. Upgrade only when your forms take off.
                </p>

                {{-- Billing period toggle --}}
                <div class="reveal mt-8 inline-flex items-center gap-1 rounded-full border border-line bg-surface p-1 shadow-sm" style="animation-delay: 240ms">
                    <button x-on:click="yearly = false" class="rounded-full px-4 py-1.5 text-sm font-semibold transition" :class="!yearly ? 'bg-ink text-canvas' : 'text-muted hover:text-ink'">Monthly</button>
                    <button x-on:click="yearly = true" class="flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-semibold transition" :class="yearly ? 'bg-ink text-canvas' : 'text-muted hover:text-ink'">
                        Yearly
                        <span class="rounded-full bg-accent px-1.5 py-0.5 text-[10px] font-bold text-accent-fg">2 months free</span>
                    </button>
                </div>
            </div>

            <div class="reveal mx-auto mt-12 grid max-w-5xl gap-5 lg:grid-cols-3" style="animation-delay: 320ms">
                @foreach ($plans as $i => $plan)
                    @php $highlight = $plan->name === 'Pro'; @endphp
                    <div class="card relative flex flex-col rounded-2xl p-7 {{ $highlight ? 'border-accent-line shadow-xl ring-4 ring-accent-soft lg:-translate-y-3' : '' }}">
                        @if ($highlight)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-accent px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-accent-fg shadow-sm">Most popular</span>
                        @endif

                        <h2 class="font-display text-xl font-extrabold">{{ $plan->name }}</h2>
                        <p class="mt-1.5 min-h-10 text-[13px] leading-relaxed text-muted">{{ $plan->description }}</p>

                        <div class="mt-5 flex items-baseline gap-1.5">
                            @if ((int) $plan->monthly_price === 0)
                                <span class="font-display text-5xl font-extrabold tracking-tight">{{ $plan->currency }}0</span>
                                <span class="text-sm text-muted">forever</span>
                            @else
                                <span class="font-display text-5xl font-extrabold tracking-tight">
                                    {{ $plan->currency }}<span x-text="yearly ? {{ round($plan->yearly_price / 12) }} : {{ $plan->monthly_price }}"></span>
                                </span>
                                <span class="text-sm text-muted">/ month</span>
                            @endif
                        </div>
                        @if ((int) $plan->monthly_price !== 0)
                            <p class="mt-1 h-4 text-xs text-subtle" x-show="yearly" x-cloak>Billed {{ $plan->currency }}{{ $plan->yearly_price }} yearly</p>
                            <p class="mt-1 h-4 text-xs text-subtle" x-show="!yearly">Billed monthly</p>
                        @else
                            <p class="mt-1 h-4 text-xs text-subtle">No credit card required</p>
                        @endif

                        <ul class="mt-6 flex-1 space-y-3">
                            @foreach ($plan->features ?? [] as $feature)
                                <li class="flex items-start gap-2.5 text-sm">
                                    <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full {{ $highlight ? 'bg-accent-soft text-accent' : 'bg-good-soft text-good' }}">
                                        <x-icon name="check" class="size-3" />
                                    </span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        <a
                            href="{{ auth()->check() ? route('settings.billing') : url('/auth/register') }}"
                            class="btn mt-7 w-full {{ $highlight ? 'btn-primary' : 'btn-outline' }}"
                        >
                            {{ (int) $plan->monthly_price === 0 ? 'Start for free' : 'Get '.$plan->name }}
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- FAQ --}}
            <div class="mx-auto mt-24 max-w-2xl" data-reveal>
                <h2 class="text-center font-display text-3xl font-extrabold tracking-tight">Questions, answered.</h2>
                <div class="mt-8 space-y-3">
                    @foreach ([
                        ['q' => 'What counts as a response?', 'a' => 'A response is one completed submission of one of your forms. Drafts, previews and your own test submissions while logged in don\'t count.'],
                        ['q' => 'What happens if I go over my limit?', 'a' => 'Your forms keep working — we never silently drop a response. We\'ll email you and give you a comfortable grace period to upgrade.'],
                        ['q' => 'Can I cancel anytime?', 'a' => 'Yes. Downgrade or cancel from your billing settings in two clicks. Your forms and responses stay yours, on the Free plan, forever.'],
                        ['q' => 'Do you offer discounts?', 'a' => 'Yearly billing gives you two months free. We also offer discounts for students, educators and non-profits — just reach out.'],
                    ] as $faq)
                        <details class="card group rounded-xl px-5 py-4 open:pb-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold [&::-webkit-details-marker]:hidden">
                                {{ $faq['q'] }}
                                <span class="text-subtle transition-transform group-open:rotate-180"><x-icon name="chevron-down" class="size-4" /></span>
                            </summary>
                            <p class="mt-3 text-sm leading-relaxed text-muted">{{ $faq['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

</x-layouts.marketing>
