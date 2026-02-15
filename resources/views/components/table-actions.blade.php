{{-- Celda de acciones para la última columna de una fila de tabla --}}
<td {{ $attributes->merge(['class' => 'px-4 py-3 text-right text-sm whitespace-nowrap']) }}>
    <div class="inline-flex items-center gap-2">
        {{ $slot }}
    </div>
</td>
