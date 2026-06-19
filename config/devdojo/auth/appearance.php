<?php

/*
 * Branding for the Formly authentication screens (devdojo/auth).
 */

return [
    'logo' => [
        'type' => 'svg',
        'image_src' => '',
        'svg_string' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="9" fill="#111110"/><rect x="11" y="8" width="4.5" height="16" rx="2.25" fill="#f5f5f2"/><rect x="11" y="8" width="11.5" height="4.5" rx="2.25" fill="#f5f5f2"/><rect x="11" y="15" width="8.5" height="4.5" rx="2.25" fill="#ec1e9b"/></svg>',
        'height' => '36',
    ],
    'background' => [
        'color' => '#f5f5f2',
        'image' => '',
        'image_overlay_color' => '#000000',
        'image_overlay_opacity' => '0.5',
    ],
    'color' => [
        'text' => '#111110',
        'button' => '#111110',
        'button_text' => '#f5f5f2',
        'input_text' => '#111110',
        'input_border' => '#d4d4d1',
    ],
    'alignment' => [
        'heading' => 'center',
        'container' => 'center',
    ],
    'favicon' => [
        'light' => '/auth/img/favicon.png',
        'dark' => '/auth/img/favicon-dark.png',
    ],
];
