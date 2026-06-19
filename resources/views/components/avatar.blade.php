@props(['user', 'class' => 'size-8'])

@php
    $hasImage = filled($user->avatar) && \Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://', '/']);
@endphp

@if ($hasImage)
    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => $class.' rounded-full object-cover']) }}>
@else
    <span {{ $attributes->merge(['class' => $class.' rounded-full bg-ink text-canvas inline-flex items-center justify-center text-xs font-bold tracking-wide select-none']) }}>
        {{ $user->initials() }}
    </span>
@endif
