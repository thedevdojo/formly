<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Feature flags
    |--------------------------------------------------------------------------
    | These are the default "on/off" states for each bundled feature package.
    | They can be overridden per-app at runtime via the foundation_settings table
    | (managed from /foundation/setup) which is merged over these defaults at boot.
    |
    | Note: all features default to ON so the foundation stays fully functional
    | out of the box. Disable any feature you don't need.
    */

    'features' => [
        'auth' => true,  // foundational — effectively always on
        'billing' => true,
        'blog' => false,
        'changelog' => true,
        'notifications' => true,
        'profiles' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature dependencies
    |--------------------------------------------------------------------------
    | Enabling a feature automatically enables its prerequisites.
    */

    'depends' => [
        'blog' => ['auth'],
        'profiles' => ['auth'],
        'billing' => ['auth'],
    ],

];
