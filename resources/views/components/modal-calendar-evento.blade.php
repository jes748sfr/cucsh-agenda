<x-modal name="ver-evento" id="modal-ver-evento" :show="false" maxWidth="2xl">
    <div x-data="{
        event: null,

        formatDate(dateString) {
            const date = new Date(dateString);
            const day = date.toLocaleDateString('es-MX', { weekday: 'long' });
             return day.charAt(0).toUpperCase() + day.slice(1);
        },

        formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('es-MX', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        },

        formatDay(dateString) {
            return new Date(dateString).getDate();
        },

        formatMonth(dateString) {
            return new Date(dateString).toLocaleDateString('es-MX', {
                month: 'short'
            }).toUpperCase();
        }
    }"
    x-on:modal-data-ver-evento.window="
        event = $event.detail.event || null;
    ">

        <!-- Header -->
        <div class="flex justify-between p-4">
            <p class="text-gray-400 text-sm font-semibold">DETALLES DEL EVENTO</p>
            <button
                type="button"
                @click="$dispatch('close-modal', 'ver-evento'); event = null;"
                class="text-gray-500 hover:text-gray-700"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Contenido -->
        <div class="p-4" x-show="event">
            <div class="flex items-stretch p-4 gap-4">
                <div class="w-32 text-center text-white text-sm font-bold rounded-xl flex flex-col justify-center"
                    x-bind:style="'background-color: ' + event.backgroundColor">
                    <p class="text-xl font-bold" x-text="event ? formatMonth(event.start) : ''"></p>
                    <p class="text-2xl font-bold" x-text="event ? formatDay(event.start) : ''"></p>
                </div>
                <div class="flex-1">
                 <P class="text-2xl font-extrabold" x-text="event.title"></P>
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 rounded-xl px-2 py-1" x-text="event.extendedProps.tipo" style="color: ${event.backgroundColor};"></span>
                        <span class="text-gray-400" x-text="`ID: ${event.id}`"></span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid grid-cols-4 gap-4 items-center">
                    <div class="w-10 h-10 bg-gray-200 p-2 rounded-lg inline-flex items-center justify-center justify-self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1 col-span-3">
                        <p class="text-gray-500 text-sm">FECHA Y HORA</p>
                        <div>
                            <span x-text="formatDate(event.start)"></span>,
                            <span x-text="formatTime(event.start)"></span> - <span x-text="formatTime(event.end)"></span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 items-center">
                    <div class="w-10 h-10 bg-gray-200 p-2 rounded-lg inline-flex items-center justify-center justify-self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1 col-span-3">
                        <p class="text-gray-500 text-sm">UBICACION</p>
                        <div>
                            <span x-text="event.extendedProps.ubicacion"></span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 items-center">
                    <div class="w-10 h-10 bg-gray-200 p-2 rounded-lg inline-flex items-center justify-center justify-self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1 col-span-3">
                        <p class="text-gray-500 text-sm">ORGANIZADOR</p>
                            <span x-text="event.extendedProps.organizador"></span>
                            <span class="text-gray-400 text-sm" x-text="`Ext. ${event.extendedProps.organizadorTel}`"></span>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 items-center">
                    <div class="w-10 h-10 bg-gray-200 p-2 rounded-lg inline-flex items-center justify-center justify-self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z" />
                            <path fill-rule="evenodd" d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z" clip-rule="evenodd" />
                            <path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1 col-span-3">
                        <p class="text-gray-500 text-sm">DEPENDENCIA</p>
                        <div>
                            <span x-text="event.extendedProps.administracion"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-stretch p-4 gap-4">
                <div class="w-full h-20 bg-gray-100 rounded-xl px-2 py-1 border-2 border-gray-200">
                    <div class="flex items-center gap-2 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                        </svg>
                        <p class="font-extrabold">NOTAS CTA</p>
                    </div>
                    <p x-text="event.extendedProps.notas_cta || 'Sin notas'"></p>
                </div>
            </div>

            <div class="flex items-stretch p-4 gap-4">
                <div class="w-full h-20 bg-gray-100 rounded-xl px-2 py-1 border-2 border-gray-200">
                    <div class="flex items-center gap-2 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="size-6">
                            <path d="M598.6 118.6C611.1 106.1 611.1 85.8 598.6 73.3C586.1 60.8 565.8 60.8 553.3 73.3L361.3 265.3L326.6 230.6C322.4 226.4 316.6 224 310.6 224C298.1 224 288 234.1 288 246.6L288 275.7L396.3 384L425.4 384C437.9 384 448 373.9 448 361.4C448 355.4 445.6 349.6 441.4 345.4L406.7 310.7L598.7 118.7zM373.1 417.4L254.6 298.9C211.9 295.2 169.4 310.6 138.8 341.2L130.8 349.2C108.5 371.5 96 401.7 96 433.2C96 440 103.1 444.4 109.2 441.4L160.3 415.9C165.3 413.4 169.8 420 165.7 423.8L39.3 537.4C34.7 541.6 32 547.6 32 553.9C32 566.1 41.9 576 54.1 576L227.4 576C266.2 576 303.3 560.6 330.8 533.2C361.4 502.6 376.7 460.1 373.1 417.4z"/>
                        </svg>
                        <p class="font-extrabold">NOTAS SERVICIOS GENERALES</p>
                    </div>
                    <p x-text="event.extendedProps.notas_servicios || 'Sin notas'"></p>
                </div>
            </div>

            <hr class="h-px my-8 border-0" x-bind:style="'background-color: ' + event.backgroundColor">

            <div class="flex items-center gap-2">

    <!-- ICONO -->
    <div class="w-8 h-8 flex items-center justify-center bg-gray-300 rounded-2xl border border-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke-width="1.5"
             stroke="currentColor"
             class="w-5 h-5 text-gray-700">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
        </svg>
    </div>

    <!-- TEXTO -->
    <span class="text-gray-400 text-sm">
        Aprobado por:
    </span>

    <span class="text-gray-800 text-sm" x-text="event.extendedProps.usuario || 'Sin registrar'"></span>
</div>
        </div>
    </div>
</x-modal>
