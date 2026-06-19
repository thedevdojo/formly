<?php

use function Laravel\Folio\{middleware, name};

middleware(['auth']);
name('dashboard');

?>

<x-layouts.app title="Forms">
    <livewire:forms-index />
</x-layouts.app>
