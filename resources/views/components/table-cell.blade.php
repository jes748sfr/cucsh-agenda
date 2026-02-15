@props([
    'align' => 'left',
])

@php
$alignmentClasses = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
@endphp

<td {{ $attributes->merge([
    'class' => "px-4 py-3 text-sm text-gray-700 {$alignmentClasses}",
]) }}>
    {{ $slot }}
</td>
