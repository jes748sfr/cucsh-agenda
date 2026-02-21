<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Usuarios</h2>
    </x-slot>

    {{-- Cabecera en el área de contenido --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
        <p class="text-sm text-gray-500 mt-1">Administra los miembros del equipo y sus permisos de acceso.</p>
    </div>

    {{-- Barra de acciones --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <p class="text-sm font-medium text-gray-700">
            Todos los usuarios
            <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                {{ $users->total() }}
            </span>
        </p>

        <div class="flex items-center gap-2">
            {{-- Filtro por rol --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    @click="open = !open"
                    @click.outside="open = false"
                    class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-udg-gold/30"
                >
                    <x-heroicon-o-funnel class="h-4 w-4 text-gray-400" />
                    Filtros
                    @if(request('rol'))
                        <span class="ml-1 h-2 w-2 rounded-full bg-primary"></span>
                    @endif
                </button>

                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 z-10 mt-1 w-48 rounded-md border border-gray-100 bg-white shadow-lg ring-1 ring-black/5"
                >
                    <div class="py-1">
                        <p class="px-3 py-1.5 text-xs font-medium text-gray-400 uppercase tracking-wider">Rol</p>
                        <a href="{{ route('users.index') }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ !request('rol') ? 'text-primary font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            @if(!request('rol'))
                                <x-heroicon-m-check class="h-4 w-4" />
                            @else
                                <span class="h-4 w-4"></span>
                            @endif
                            Todos
                        </a>
                        <a href="{{ route('users.index', ['rol' => 'administrador']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ request('rol') === 'administrador' ? 'text-primary font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            @if(request('rol') === 'administrador')
                                <x-heroicon-m-check class="h-4 w-4" />
                            @else
                                <span class="h-4 w-4"></span>
                            @endif
                            Administrador
                        </a>
                        <a href="{{ route('users.index', ['rol' => 'editor']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ request('rol') === 'editor' ? 'text-primary font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            @if(request('rol') === 'editor')
                                <x-heroicon-m-check class="h-4 w-4" />
                            @else
                                <span class="h-4 w-4"></span>
                            @endif
                            Editor
                        </a>
                        <a href="{{ route('users.index', ['rol' => 'consultor']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ request('rol') === 'consultor' ? 'text-primary font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            @if(request('rol') === 'consultor')
                                <x-heroicon-m-check class="h-4 w-4" />
                            @else
                                <span class="h-4 w-4"></span>
                            @endif
                            Consultor
                        </a>
                    </div>
                </div>
            </div>

            {{-- Botón agregar --}}
            @can('usuarios.crear')
                <a href="{{ route('users.create') }}">
                    <x-primary-button>
                        <x-heroicon-o-user-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Agregar usuario
                    </x-primary-button>
                </a>
            @endcan
        </div>
    </div>

    {{-- Tabla --}}
    <x-table>
        <thead class="bg-gray-50">
            <tr>
                <x-table-header>Usuario</x-table-header>
                <x-table-header>Rol</x-table-header>
                <x-table-header>Registrado</x-table-header>
                <x-table-header align="right">Acciones</x-table-header>
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
                @php
                    $rolNombre = $user->roles->first()?->name ?? 'sin rol';
                    $badgeColor = match($rolNombre) {
                        'administrador' => 'primary',
                        'editor'        => 'warning',
                        default         => 'gray',
                    };
                    $esProtegido = $user->id === 1;
                    $esPropioUsuario = Auth::id() === $user->id;
                @endphp
                <x-table-row striped>
                    {{-- Nombre + email --}}
                    <x-table-cell>
                        <div class="flex items-center gap-3">
                            {{-- Iniciales --}}
                            <div class="h-8 w-8 flex-shrink-0 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-semibold text-primary">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('users.show', $user) }}"
                                   class="font-medium text-gray-900 hover:text-primary transition-colors">
                                    {{ $user->name }}
                                    @if($esPropioUsuario)
                                        <span class="ml-1.5 text-xs font-normal text-gray-400">(tú)</span>
                                    @endif
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </x-table-cell>

                    {{-- Rol --}}
                    <x-table-cell>
                        <x-badge :color="$badgeColor">{{ ucfirst($rolNombre) }}</x-badge>
                    </x-table-cell>

                    {{-- Fecha de registro --}}
                    <x-table-cell>
                        <span class="text-sm text-gray-600">
                            {{ $user->created_at->translatedFormat('d M Y') }}
                        </span>
                    </x-table-cell>

                    {{-- Acciones --}}
                    <x-table-actions>
                        @if(!$esProtegido)
                            @can('usuarios.editar')
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-gray-400 hover:text-primary transition"
                                   title="Editar">
                                    <x-heroicon-o-pencil-square class="h-5 w-5" />
                                </a>
                            @endcan
                        @endif

                        @if(!$esProtegido && !$esPropioUsuario)
                            @can('usuarios.eliminar')
                                <button type="button"
                                    x-on:click="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                    class="text-gray-400 hover:text-danger transition"
                                    title="Eliminar">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            @endcan
                        @endif

                        @if($esProtegido)
                            <span class="text-xs text-gray-400 italic" title="Cuenta de sistema protegida">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                            </span>
                        @endif
                    </x-table-actions>
                </x-table-row>

                @if(!$esProtegido && !$esPropioUsuario)
                    @can('usuarios.eliminar')
                        <x-confirm-delete-modal
                            name="delete-user-{{ $user->id }}"
                            :action="route('users.destroy', $user)"
                            title="Eliminar usuario"
                            :description="'Se eliminará la cuenta de «' . $user->name . '». Esta acción no se puede deshacer.'"
                        />
                    @endcan
                @endif
            @empty
                <x-table-row>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <x-heroicon-o-users class="mx-auto h-10 w-10 text-gray-300" />
                        <p class="mt-2 text-sm font-medium text-gray-700">No hay usuarios registrados</p>
                        <p class="mt-1 text-xs text-gray-400">Agrega el primer usuario con el botón de arriba.</p>
                    </td>
                </x-table-row>
            @endforelse
        </tbody>
    </x-table>

    @if ($users->hasPages())
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif

</x-app-layout>
