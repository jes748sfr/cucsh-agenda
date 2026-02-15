@props([
    'striped' => false,
])

<tr {{ $attributes->merge([
    'class' => 'border-b border-gray-100 transition-colors duration-150 hover:bg-gray-50' . ($striped ? ' even:bg-gray-50/50' : ''),
]) }}>
    {{ $slot }}
</tr>
