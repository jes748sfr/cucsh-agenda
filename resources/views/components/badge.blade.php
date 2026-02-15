@props([
    'color' => 'gray',
    'dot' => false,
    'size' => 'sm',
])

@php
$colorClasses = match($color) {
    'green', 'success' => 'bg-green-50 text-green-700 ring-green-600/20',
    'red', 'danger'    => 'bg-red-50 text-red-700 ring-red-600/20',
    'yellow', 'warning' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
    'blue', 'info'     => 'bg-blue-50 text-blue-700 ring-blue-600/20',
    'primary'          => 'bg-primary/5 text-primary ring-primary/20',
    default            => 'bg-gray-50 text-gray-600 ring-gray-500/10',
};

$dotColorClasses = match($color) {
    'green', 'success' => 'bg-green-500',
    'red', 'danger'    => 'bg-red-500',
    'yellow', 'warning' => 'bg-yellow-500',
    'blue', 'info'     => 'bg-blue-500',
    'primary'          => 'bg-primary',
    default            => 'bg-gray-400',
};

$sizeClasses = match($size) {
    'xs' => 'px-1.5 py-0.5 text-[0.625rem]',
    'md' => 'px-2.5 py-1 text-xs',
    default => 'px-2 py-0.5 text-xs', // sm
};
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-x-1.5 rounded-full font-medium ring-1 ring-inset {$colorClasses} {$sizeClasses}",
]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotColorClasses }}" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</span>
