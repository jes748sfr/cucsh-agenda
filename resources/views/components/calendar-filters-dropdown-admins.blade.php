{{--
    Dropdown checklist de administraciones — sub-componente interno de calendar-filters.
    Compartido entre dashboard y calendario publico.
--}}

<div x-show="adminsOpen"
     x-transition:enter="transition ease-out duration-100"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-75"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     @click.outside="adminsOpen = false"
     class="absolute left-0 top-full mt-1 z-50 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 px-3"
>
    <label class="flex items-center gap-2 py-1.5 cursor-pointer">
        <input type="checkbox"
               :checked="allAdminsSelected"
               @change="toggleAllAdmins($event.target.checked)"
               class="rounded border-gray-300 text-udg-blue focus:ring-udg-gold/30 h-4 w-4" />
        <span class="text-sm font-medium text-gray-900">Todas</span>
    </label>
    <div class="border-t border-gray-100 my-1"></div>
    @foreach ($administraciones as $admin)
        <label class="flex items-center gap-2 py-1 cursor-pointer">
            <input type="checkbox"
                   value="{{ $admin->id }}"
                   :checked="adminSeleccionadas.includes({{ $admin->id }})"
                   @change="toggleAdmin({{ $admin->id }}, $event.target.checked)"
                   class="rounded border-gray-300 text-udg-blue focus:ring-udg-gold/30 h-4 w-4" />
            <span class="text-sm text-gray-700">{{ $admin->nombre }}</span>
        </label>
    @endforeach
    <div class="border-t border-gray-100 mt-2 pt-2">
        <button type="button"
                class="w-full text-center text-sm font-medium text-white bg-udg-blue rounded-md py-1.5 hover:bg-udg-blue/90 transition-colors"
                @click="aplicarAdmins()"
        >
            Aplicar
        </button>
    </div>
</div>
