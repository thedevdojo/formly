<?php

use function Laravel\Folio\{middleware, name};

middleware(['auth']);
name('settings.security');

?>

<x-layouts.app title="Security · Settings">
    <div class="mx-auto max-w-2xl">
        <x-app.settings-tabs />
        <div class="mt-8">
            <livewire:settings.security />
        </div>
    </div>
</x-layouts.app>
