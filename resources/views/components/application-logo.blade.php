<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-x-3']) }}>
    {{-- Logo --}}
    <img src="{{ asset('images/escudo-udg.png') }}"
         alt="Escudo Universidad de Guadalajara"
         class="h-12 w-auto flex-shrink-0">

    {{-- Contenedor de Texto --}}
    <div class="flex flex-col justify-center align-middle">
        {{-- Título --}}
        <span class="text-sm font-bold leading-none text-primary tracking-wide">
            CUCSH
        </span>

        {{-- Subtítulo --}}
        <span class="mt-0.5 text-xs leading-tight text-gray-500 w-[2rem]">
            Agenda universitaria
        </span>
    </div>
</div>
