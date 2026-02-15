@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de páginas" class="flex justify-between">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-400 cursor-default">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                Anterior
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 transition" aria-label="Página anterior">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                Anterior
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 transition" aria-label="Página siguiente">
                Siguiente
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </a>
        @else
            <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-400 cursor-default">
                Siguiente
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </span>
        @endif
    </nav>
@endif
