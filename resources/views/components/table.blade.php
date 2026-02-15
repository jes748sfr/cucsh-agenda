@props([
    'header' => null,
])

<section {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200']) }}>
    {{-- Encabezado opcional: título, búsqueda, filtros, botones de acción --}}
    @if ($header)
        <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
            {{ $header }}
        </div>
    @endif

    {{-- Contenedor responsive con scroll horizontal en móvil --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            {{ $slot }}
        </table>
    </div>

    {{-- Pie de tabla opcional: paginación, contadores --}}
    @if (isset($footer))
        <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</section>
