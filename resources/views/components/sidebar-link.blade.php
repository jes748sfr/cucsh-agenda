@props([
    'href',
    'active' => false,
    'icon' => null,
])

@php
$classes = $active
    ? 'flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium bg-primary/5 text-primary'
    : 'flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon" class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>
