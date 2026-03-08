@props([
    'size' => 'default',
    'showText' => true,
])

@php
    $imgSizes = [
        'sm' => 'h-7',
        'default' => 'h-9',
        'lg' => 'h-11',
    ];
    $imgClass = $imgSizes[$size] ?? $imgSizes['default'];
    $imgFile = 'escudo-udg.png';
@endphp

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 flex-shrink-0']) }}>
    <img src="{{ asset('images/' . $imgFile) }}" alt="Escudo UDG" class="{{ $imgClass }} w-auto">
    @if($showText)
        <div class="hidden sm:block">
            <span class="text-sm font-bold text-udg-blue leading-none">Agenda CUCSH</span>
            <span class="block text-[0.65rem] text-gray-500 leading-tight">Universidad de Guadalajara</span>
        </div>
    @endif
</a>
