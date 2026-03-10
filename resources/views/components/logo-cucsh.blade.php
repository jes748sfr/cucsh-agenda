@props([
    'size' => 'default',
    'showText' => true,
])

@php
    $imgSizes = [
        'sm' => 'h-7',
        'default' => 'h-9',
        'lg' => 'h-11',
        'xl' => 'h-14',
    ];
    $imgClass = $imgSizes[$size] ?? $imgSizes['default'];
    $imgFile = 'escudo-udg.png';

    $textSizes = [
        'sm' => ['title' => 'text-xs', 'sub' => 'text-[0.6rem]'],
        'default' => ['title' => 'text-sm', 'sub' => 'text-[0.65rem]'],
        'lg' => ['title' => 'text-sm', 'sub' => 'text-[0.65rem]'],
        'xl' => ['title' => 'text-base', 'sub' => 'text-xs'],
    ];
    $txtClass = $textSizes[$size] ?? $textSizes['default'];
@endphp

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 flex-shrink-0']) }}>
    <img src="{{ asset('images/' . $imgFile) }}" alt="Escudo UDG" class="{{ $imgClass }} w-auto">
    @if($showText)
        <div class="hidden sm:block">
            <span class="{{ $txtClass['title'] }} font-bold text-udg-blue leading-none">Agenda CUCSH</span>
            <span class="block {{ $txtClass['sub'] }} text-gray-500 leading-tight">Universidad de Guadalajara</span>
        </div>
    @endif
</a>
