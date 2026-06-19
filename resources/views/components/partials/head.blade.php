@props(['title' => null, 'description' => null])

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ? $title.' · Formly' : 'Formly — Forms people actually finish' }}</title>
<meta name="description" content="{{ $description ?? 'Formly is the fastest way to build beautiful forms. Create a form like writing a doc, share a link, and watch the responses roll in.' }}">

<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='9' fill='%23111110'/%3E%3Crect x='11' y='8' width='4.5' height='16' rx='2.25' fill='%23f5f5f2'/%3E%3Crect x='11' y='8' width='11.5' height='4.5' rx='2.25' fill='%23f5f5f2'/%3E%3Crect x='11' y='15' width='8.5' height='4.5' rx='2.25' fill='%23ec1e9b'/%3E%3C/svg%3E">

<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
