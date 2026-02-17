@props([
    'href',
    'active' => false,
])

@php
$classes = $active
    ? 'block rounded-lg py-1.5 pl-11 pr-3 text-sm font-medium text-primary bg-primary/5'
    : 'block rounded-lg py-1.5 pl-11 pr-3 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>