{{--
    Dropdown de instituciones — sub-componente interno de calendar-filters.
    Compartido entre dashboard y calendario publico.
--}}

<div x-show="institucionOpen"
     x-transition:enter="transition ease-out duration-100"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-75"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     @click.outside="institucionOpen = false"
     class="absolute left-0 top-full mt-1 z-50 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
>
    <button type="button"
            class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50 transition-colors"
            :class="!institucionId && 'font-semibold text-udg-blue'"
            @click="selectInstitucion(null, null); institucionOpen = false"
    >
        Todas las instituciones
    </button>
    <div class="border-t border-gray-100 my-0.5"></div>
    @foreach ($instituciones as $inst)
        <button type="button"
                class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50 transition-colors"
                :class="institucionId == {{ $inst->id }} && 'font-semibold text-udg-blue'"
                @click="selectInstitucion({{ $inst->id }}, {{ Js::from($inst->nombre) }}); institucionOpen = false"
        >
            {{ $inst->nombre }}
        </button>
    @endforeach
</div>
