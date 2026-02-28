@props(['administraciones'])

<x-modal name="crear-organizador" maxWidth="md" focusable>
    <div
        x-data="{
            loading: false,
            nombre: '',
            email: '',
            tel: '',
            administracion_id: '',
            errors: {},
            resetForm() {
                this.nombre = '';
                this.email = '';
                this.tel = '';
                this.administracion_id = '';
                this.errors = {};
                this.loading = false;
            },
            async submit() {
                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch('{{ url('organizadores') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({
                            nombre: this.nombre,
                            email: this.email,
                            tel: this.tel || null,
                            administracion_id: this.administracion_id,
                        }),
                    });

                    if (response.status === 422) {
                        const data = await response.json();
                        this.errors = data.errors || {};
                        this.loading = false;
                        return;
                    }

                    if (!response.ok) {
                        this.errors = { nombre: ['Ocurrió un error inesperado. Intenta de nuevo.'] };
                        this.loading = false;
                        return;
                    }

                    const organizador = await response.json();
                    this.$dispatch('organizador-created', organizador);
                    this.$dispatch('close');
                    this.resetForm();
                } catch (e) {
                    this.errors = { nombre: ['Error de conexión. Verifica tu red e intenta de nuevo.'] };
                    this.loading = false;
                }
            }
        }"
        @open-modal.window="if ($event.detail === 'crear-organizador') resetForm()"
        class="p-6"
    >
        {{-- Icono --}}
        <div class="mb-4 inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary/10">
            <x-heroicon-o-user-plus class="h-5 w-5 text-primary" aria-hidden="true" />
        </div>

        <h3 class="text-lg font-semibold text-gray-900">Nuevo organizador</h3>
        <p class="mt-1 text-sm text-gray-500">Registra un nuevo organizador sin salir del formulario.</p>

        <form @submit.prevent="submit" class="mt-5 space-y-4">

            {{-- Nombre --}}
            <div>
                <x-input-label for="org-nombre" value="Nombre *" />
                <x-text-input
                    id="org-nombre"
                    type="text"
                    class="mt-1 block w-full"
                    x-model="nombre"
                    maxlength="255"
                    placeholder="Nombre completo del organizador"
                    aria-describedby="org-nombre-error"
                />
                <template x-if="errors.nombre">
                    <p class="mt-1 text-sm text-danger" id="org-nombre-error" x-text="errors.nombre[0]"></p>
                </template>
            </div>

            {{-- Email --}}
            <div>
                <x-input-label for="org-email" value="Correo electrónico *" />
                <x-text-input
                    id="org-email"
                    type="email"
                    class="mt-1 block w-full"
                    x-model="email"
                    maxlength="255"
                    placeholder="correo@cucsh.udg.mx"
                    aria-describedby="org-email-error"
                />
                <template x-if="errors.email">
                    <p class="mt-1 text-sm text-danger" id="org-email-error" x-text="errors.email[0]"></p>
                </template>
            </div>

            {{-- Teléfono --}}
            <div>
                <x-input-label for="org-tel" value="Teléfono" />
                <x-text-input
                    id="org-tel"
                    type="text"
                    class="mt-1 block w-full"
                    x-model="tel"
                    maxlength="20"
                    placeholder="Opcional"
                    aria-describedby="org-tel-error"
                />
                <template x-if="errors.tel">
                    <p class="mt-1 text-sm text-danger" id="org-tel-error" x-text="errors.tel[0]"></p>
                </template>
            </div>

            {{-- Administración --}}
            <div>
                <x-input-label for="org-administracion" value="Administración *" />
                <select
                    id="org-administracion"
                    x-model="administracion_id"
                    aria-describedby="org-administracion-error"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                >
                    <option value="">— Seleccionar —</option>
                    @foreach ($administraciones as $adm)
                        <option value="{{ $adm->id }}">{{ $adm->nombre }}</option>
                    @endforeach
                </select>
                <template x-if="errors.administracion_id">
                    <p class="mt-1 text-sm text-danger" id="org-administracion-error" x-text="errors.administracion_id[0]"></p>
                </template>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="$dispatch('close')" :disabled="false" x-bind:disabled="loading">
                    Cancelar
                </x-secondary-button>

                <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-udg-blue focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading" class="inline-flex items-center gap-1.5">
                        <x-heroicon-o-check class="h-4 w-4" />
                        Crear organizador
                    </span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Creando...
                    </span>
                </button>
            </div>

        </form>
    </div>
</x-modal>
