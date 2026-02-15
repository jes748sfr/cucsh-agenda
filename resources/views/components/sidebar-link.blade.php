@props([
    'href',
    'active' => false,
    'icon' => null,
])

@php
$classes = $active
    ? 'flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium bg-blue-50 text-primary'
    : 'flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}
   x-bind:title="!sidebarOpen ? @js(trim($slot->toHtml())) : ''">
    @if ($icon)
        <x-dynamic-component :component="$icon" class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
    @endif
    <span class="truncate whitespace-nowrap transition-opacity duration-300"
          :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">{{ $slot }}</span>
</a>