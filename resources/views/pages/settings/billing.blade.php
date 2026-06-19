<?php

use function Laravel\Folio\{middleware, name};

middleware(['auth']);
name('settings.billing');

?>

<x-layouts.app title="Billing · Settings">
    <div class="mx-auto max-w-2xl">
        <x-app.settings-tabs />
        <div class="mt-8">
            <livewire:settings.billing />
        </div>
    </div>
</x-layouts.app>
