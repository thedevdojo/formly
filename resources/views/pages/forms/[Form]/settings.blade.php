<?php

use function Laravel\Folio\{middleware, name};

middleware(['auth']);
name('forms.settings');

?>

@php
    abort_unless($form->user_id === auth()->id(), 404);
    $form->loadCount('entries');
@endphp

<x-layouts.app :title="$form->name.' — Settings'">
    <x-slot:breadcrumb>
        <a href="{{ route('dashboard') }}" class="font-medium text-muted transition hover:text-ink">Forms</a>
        <x-icon name="chevron-right" class="size-3.5 text-subtle" />
        <span class="truncate font-semibold">{{ $form->name }}</span>
    </x-slot:breadcrumb>

    <x-slot:subnav>
        <x-app.form-tabs :form="$form" active="settings" />
    </x-slot:subnav>

    <livewire:form-settings :form="$form" />
</x-layouts.app>
