<?php

use App\Models\Form;

use function Laravel\Folio\name;

name('forms.fill');

?>

@php
    $form = Form::where('slug', $slug)->firstOrFail();

    // Drafts are visible to their owner only (as a live preview).
    $isOwner = auth()->check() && auth()->id() === $form->user_id;

    if ($form->isDraft() && ! $isOwner) {
        abort(404);
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-partials.head :title="$form->name" :description="'Respond to '.$form->name.' — powered by Formly.'" />
</head>
<body class="min-h-screen bg-canvas-deep paper-grain text-ink antialiased">

    <main class="mx-auto flex min-h-screen w-full max-w-2xl flex-col px-4 py-8 sm:py-14">

        @if ($form->isDraft() && $isOwner)
            <div class="mb-5 flex items-center justify-between gap-3 rounded-xl border border-warn/30 bg-warn-soft px-4 py-2.5 text-sm font-medium text-warn">
                <span class="flex items-center gap-2"><x-icon name="eye" class="size-4" /> Draft preview — only you can see this.</span>
                <a href="{{ route('forms.edit', ['form' => $form]) }}" class="font-bold underline underline-offset-2">Back to editor</a>
            </div>
        @endif

        @if ($form->isClosed())
            <div class="card flex flex-1 flex-col items-center justify-center rounded-3xl px-8 py-20 text-center sm:px-14">
                <span class="flex size-14 items-center justify-center rounded-2xl bg-ink-soft text-muted">
                    <x-icon name="lock" class="size-7" />
                </span>
                <h1 class="mt-6 font-display text-3xl font-extrabold tracking-tight">{{ $form->name }}</h1>
                <p class="mx-auto mt-3 max-w-sm text-muted">{{ $form->setting('closed_message') }}</p>
            </div>
        @else
            <livewire:form-fill :form="$form" :preview="$form->isDraft()" />
        @endif

        @if ($form->setting('show_branding'))
            <footer class="mt-6 pb-2 text-center">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-full border border-line bg-surface px-3.5 py-1.5 text-xs font-semibold text-muted shadow-sm transition hover:text-ink">
                    Made with
                    <span class="inline-flex items-center gap-1">
                        <x-logo-icon class="size-3.5" />
                        <span class="text-sm font-extrabold tracking-tight">Formly</span>
                    </span>
                </a>
            </footer>
        @endif
    </main>
</body>
</html>
