{{-- Componente de breadcrumbs reutilizable
     Uso: <x-breadcrumb :items="[['label' => 'Sección', 'url' => '/ruta'], ['label' => 'Página actual']]" />
     El último item se muestra como texto (página actual), los anteriores como enlaces.
--}}
@props(['items' => []])

<nav class="flex items-center gap-1.5 text-sm" aria-label="Breadcrumb">
    @foreach ($items as $i => $item)
        @if ($i > 0)
            <x-heroicon-m-chevron-right class="h-3.5 w-3.5 flex-shrink-0 text-gray-400" />
        @endif

        @if ($loop->last)
            <span class="font-semibold text-gray-900 truncate">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}"
               class="text-gray-500 hover:text-primary transition truncate">
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</nav>
