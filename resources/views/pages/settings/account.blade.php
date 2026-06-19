<?php

use function Laravel\Folio\{middleware, name};

middleware(['auth']);
name('settings.account');

?>

<x-layouts.app title="Account · Settings">
    <div class="mx-auto max-w-2xl">
        <x-app.settings-tabs />
        <div class="mt-8">
            <livewire:settings.account />
        </div>
    </div>
</x-layouts.app>
