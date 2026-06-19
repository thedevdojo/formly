<?php

use function Laravel\Folio\name;

name('templates');

?>

<x-layouts.marketing title="Templates" description="Start from a beautifully crafted form template — contact forms, RSVPs, job applications, surveys and more.">

    <section class="relative overflow-hidden">
        <div class="relative mx-auto max-w-6xl px-5 pb-20 pt-16 sm:pt-24">
            <x-doodle name="plane" class="reveal absolute left-[6%] top-20 size-24 text-ink max-lg:hidden" style="animation-delay: 500ms" />
            <x-doodle name="sparkle" class="reveal absolute right-[12%] top-12 size-10 rotate-45 text-ink max-lg:hidden" style="animation-delay: 600ms" />
            <x-doodle name="squiggle" class="reveal absolute right-[7%] top-36 size-12 text-accent max-lg:hidden" style="animation-delay: 700ms" />
            <div class="mx-auto max-w-2xl text-center">
                <p class="reveal flex items-center justify-center gap-2 text-sm font-bold text-accent" style="animation-delay: 0ms">
                    <x-icon name="template" class="size-4" /> Templates
                </p>
                <h1 class="reveal mt-3 font-display text-5xl font-extrabold tracking-tight sm:text-6xl" style="animation-delay: 80ms">
                    Start from <span class="underline-squiggle">done.</span>
                </h1>
                <p class="reveal mx-auto mt-5 max-w-lg text-lg text-muted" style="animation-delay: 160ms">
                    Every template is a real, working form. Pick one, make it yours in the editor, and publish in minutes.
                </p>
            </div>

            <div class="reveal mt-14" style="animation-delay: 280ms">
                <livewire:template-gallery />
            </div>

            <div class="mt-16 text-center" data-reveal>
                <p class="text-sm text-muted">Don't see what you need?</p>
                <a href="{{ auth()->check() ? route('dashboard') : url('/auth/register') }}" class="btn btn-ink mt-4">
                    Start from a blank form
                    <x-icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </div>
    </section>

</x-layouts.marketing>
