@props([
    'label',
    'icon' => null,
    'active' => false,
])

<div x-data="{ expanded: @js($active) }">
    {{-- Trigger --}}
    <button @click="expanded = !expanded"
            type="button"
            class="flex w-full items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $active ? 'text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
            x-bind:title="!sidebarOpen ? '{{ $label }}' : ''"
            :aria-expanded="expanded">
        @if ($icon)
            <x-dynamic-component :component="$icon" class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
        @endif
        <span class="flex-1 text-left truncate whitespace-nowrap transition-opacity duration-300"
              :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">{{ $label }}</span>
        <svg class="h-4 w-4 flex-shrink-0 transition-all duration-300"
             :class="[
                 expanded ? 'rotate-180' : '',
                 sidebarOpen ? 'opacity-100' : 'opacity-0'
             ]"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    {{-- Subitems --}}
    <div x-show="sidebarOpen && expanded"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="mt-1 space-y-0.5">
        {{ $slot }}
    </div>
</div>