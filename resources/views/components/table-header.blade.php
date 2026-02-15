@props([
    'sortable' => false,
    'align' => 'left',
])

@php
$alignmentClasses = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
@endphp

<th {{ $attributes->merge([
    'class' => "px-4 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 {$alignmentClasses}",
    'scope' => 'col',
]) }}>
    {{ $slot }}
</th>
