<?php

use Devdojo\Changelog\Models\Changelog;

use function Laravel\Folio\name;

name('changelog.index');

?>

@php
    $changelogs = Changelog::orderByDesc('created_at')->get();

    // Viewing the changelog clears the "what's new" indicator.
    if (auth()->check() && $changelogs->isNotEmpty()) {
        auth()->user()->changelogs()->syncWithoutDetaching($changelogs->pluck('id'));
    }
@endphp

<x-layouts.marketing title="Changelog" description="New features, improvements and fixes — everything we've shipped in Formly.">

    <section class="relative mx-auto max-w-3xl px-5 pb-24 pt-16 sm:pt-24">
        <x-doodle name="sparkle" class="reveal absolute -left-20 top-16 size-10 -rotate-12 text-ink max-lg:hidden" style="animation-delay: 500ms" />
        <x-doodle name="plane" class="reveal absolute -right-32 top-24 size-20 text-ink max-lg:hidden" style="animation-delay: 600ms" />
        <div class="text-center">
            <p class="reveal flex items-center justify-center gap-2 text-sm font-bold text-accent" style="animation-delay: 0ms">
                <x-icon name="megaphone" class="size-4" /> Changelog
            </p>
            <h1 class="reveal mt-3 font-display text-5xl font-extrabold tracking-tight" style="animation-delay: 80ms">
                Always <span class="underline-squiggle">shipping.</span>
            </h1>
            <p class="reveal mt-4 text-lg text-muted" style="animation-delay: 160ms">Everything new in Formly, in one place.</p>
        </div>

        <div class="reveal relative mt-16 space-y-12 before:absolute before:inset-y-2 before:left-[7px] before:w-px before:bg-line-strong sm:before:left-[103px]" style="animation-delay: 280ms">
            @forelse ($changelogs as $changelog)
                <article class="relative grid gap-3 pl-8 sm:grid-cols-[80px_1fr] sm:gap-10 sm:pl-0">
                    <span class="absolute left-0 top-1.5 size-[15px] rounded-full border-[3px] border-canvas bg-accent ring-1 ring-accent sm:left-24"></span>
                    <time class="pt-0.5 text-xs font-semibold uppercase tracking-wide text-subtle sm:text-right" datetime="{{ $changelog->created_at->toDateString() }}">
                        {{ $changelog->created_at->format('M j, Y') }}
                    </time>
                    <div class="sm:pl-10">
                        <h2 class="font-display text-2xl font-extrabold tracking-tight">{{ $changelog->title }}</h2>
                        <p class="mt-1 text-sm font-medium text-accent">{{ $changelog->description }}</p>
                        <div class="mt-3 space-y-3 text-sm leading-relaxed text-muted">
                            @foreach (explode("\n\n", $changelog->body ?? '') as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-center text-muted">Nothing here yet — check back soon!</p>
            @endforelse
        </div>
    </section>

</x-layouts.marketing>
