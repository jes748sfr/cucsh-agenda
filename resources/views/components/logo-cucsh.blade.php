@props([
    'size' => 'default',
    'showText' => true,
])

@php
    $sizes = [
        'sm'      => ['box' => 'w-7 h-7',   'radius' => 'rounded',    'title' => 'text-xs',   'sub' => 'text-[0.6rem]'],
        'default' => ['box' => 'w-9 h-9',   'radius' => 'rounded-md', 'title' => 'text-sm',   'sub' => 'text-[0.65rem]'],
        'lg'      => ['box' => 'w-11 h-11', 'radius' => 'rounded-lg', 'title' => 'text-sm',   'sub' => 'text-[0.65rem]'],
        'xl'      => ['box' => 'w-14 h-14', 'radius' => 'rounded-xl', 'title' => 'text-base', 'sub' => 'text-xs'],
    ];
    $s = $sizes[$size] ?? $sizes['default'];
@endphp

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 flex-shrink-0']) }}>
    <span class="{{ $s['box'] }} {{ $s['radius'] }} overflow-hidden flex-shrink-0">
        <img src="{{ asset('images/escudo-cucsh.png') }}" alt="CUCSH" class="w-full h-full object-cover">
    </span>
    @if($showText)
        <div class="hidden sm:block">
            <span class="{{ $s['title'] }} font-bold text-udg-blue leading-none">Agenda CUCSH</span>
            <span class="block {{ $s['sub'] }} text-gray-500 leading-tight">Universidad de Guadalajara</span>
        </div>
    @endif
</a>
