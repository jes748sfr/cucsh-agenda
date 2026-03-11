{{--
    Panel lateral de detalle de evento (modo single + modo list con acordeon).
    Compartido entre dashboard y calendario publico.

    Props:
    - $showFooter (bool): mostrar link "Ver evento completo" (default false)
    - $eventoBaseUrl (string): URL base para links a eventos (default '')
--}}

@props([
    'showFooter' => false,
    'eventoBaseUrl' => '',
])

{{-- Contenedor flex-row: calendario + panel lateral --}}
<div class="flex-1 min-h-0 flex flex-row overflow-hidden"
     x-data="eventPanel()"
     x-on:show-event-panel.window="openPanel($event.detail)"
     x-on:show-event-list.window="openListPanel($event.detail)"
     x-on:close-event-panel.window="closePanel()"
     x-on:calendar-date-change.window="onViewChange($event.detail.viewType)"
>
    <div id="calendar" class="flex-1 min-h-0 min-w-0"></div>

    {{-- Panel lateral de detalle (solo timeGridDay desktop) --}}
    <div x-show="panelOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="translate-x-full opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-x-0 opacity-100"
         x-transition:leave-end="translate-x-full opacity-0"
         class="w-80 flex-shrink-0 border-l border-gray-200 bg-white flex flex-col overflow-hidden"
    >
        {{-- Cabecera fija --}}
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 flex-shrink-0">
            <h3 class="text-sm font-semibold text-gray-900"
                x-text="panelMode === 'list' ? (panelEventos.length + ' eventos agendados') : 'Detalles del Evento'"
            ></h3>
            <button type="button"
                    @click="closePanel()"
                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                    title="Cerrar panel"
            >
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        {{-- ══ Modo single: detalle de un evento ══ --}}
        <div x-show="panelMode === 'single'" class="flex-1 flex flex-col overflow-hidden">
                {{-- Contenido scrollable --}}
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">

                    {{-- Titulo del evento --}}
                    <h4 class="text-lg font-bold text-gray-900 leading-snug"
                        x-text="panelEvento.title || ''"
                    ></h4>

                    {{-- Separador --}}
                    <div class="border-t border-gray-100"></div>

                    {{-- Institucion --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-0.5">Institucion</p>
                        <p class="text-sm text-gray-900" x-text="panelEvento.institucion || 'Sin institucion'"></p>
                    </div>

                    {{-- Organizador + Administracion --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-0.5">Organizador</p>
                        <p class="text-sm text-gray-900" x-text="panelEvento.organizador || 'Sin organizador'"></p>
                        <p x-show="panelEvento.administracion"
                           class="text-xs text-gray-400 mt-0.5"
                           x-text="panelEvento.administracion"
                        ></p>
                    </div>

                    {{-- Tipo de evento --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-0.5">Tipo de evento</p>
                        <p class="text-sm text-gray-900" x-text="panelEvento.tipo || 'Sin tipo'"></p>
                    </div>

                    {{-- Ubicacion --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-0.5">Ubicacion</p>
                        <p class="text-sm text-gray-900" x-text="panelEvento.ubicacion || 'Sin ubicacion registrada'"></p>
                    </div>

                    {{-- Horario --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-0.5">Horario</p>
                        <p class="text-sm text-gray-900" x-text="panelEvento.fechaTexto || ''"></p>
                        <p class="text-sm text-gray-600" x-text="panelEvento.horarioTexto || ''"></p>
                    </div>

                    {{-- Notas CTA (condicional) --}}
                    <template x-if="panelEvento.notas_cta">
                        <div>
                            <div class="border-t border-gray-100 mb-3"></div>
                            <p class="text-xs font-semibold text-gray-500 mb-0.5">Notas CTA</p>
                            <p class="text-sm text-gray-700 whitespace-pre-line" x-text="panelEvento.notas_cta"></p>
                        </div>
                    </template>

                </div>

                {{-- Pie fijo (solo si showFooter es true) --}}
                @if($showFooter)
                    <div class="px-5 py-3 border-t border-gray-100 flex-shrink-0">
                        <a :href="'{{ $eventoBaseUrl }}/' + panelEvento.evento_id"
                           class="flex items-center justify-center gap-2 w-full px-4 py-2 text-sm font-medium text-white bg-udg-blue rounded-lg hover:bg-udg-blue/90 transition-colors"
                        >
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                            Ver evento completo
                        </a>
                    </div>
                @endif
        </div>

        {{-- ══ Modo list: lista de eventos agrupados (acordeon) ══ --}}
        <div x-show="panelMode === 'list'" class="flex-1 overflow-y-auto px-4 py-3 space-y-2">
                <template x-for="(ev, idx) in panelEventos" :key="idx">
                    <div class="rounded-lg border border-gray-200 overflow-hidden transition-colors"
                         :class="expandedIdx === idx ? 'border-gray-300 bg-gray-50/50' : 'hover:border-gray-300'"
                    >
                        {{-- Cabecera clickable --}}
                        <button type="button"
                                class="w-full text-left p-3 hover:bg-gray-50 transition-colors"
                                @click="toggleAccordion(idx)"
                        >
                            <div class="flex items-start gap-3">
                                {{-- Indicador de color vertical --}}
                                <div class="w-1 rounded-full self-stretch flex-shrink-0"
                                     :style="'background-color:' + (ev.backgroundColor || '#7FBCD2')"
                                ></div>
                                <div class="flex-1 min-w-0">
                                    {{-- Titulo + dot importante --}}
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate"
                                           :class="expandedIdx === idx && 'text-udg-blue'"
                                           x-text="ev.title"
                                        ></p>
                                        <span x-show="ev.backgroundColor === '#FF6868'"
                                              class="w-2 h-2 rounded-full bg-[#FF6868] flex-shrink-0"
                                              style="animation: fc-pulse 2s ease-in-out infinite"
                                        ></span>
                                    </div>
                                    {{-- Horario --}}
                                    <p class="text-xs text-gray-500 mt-0.5" x-text="ev.horario"></p>
                                    {{-- Tipo + Organizador (resumen cuando cerrado) --}}
                                    <div x-show="expandedIdx !== idx" class="flex items-center gap-2 mt-1">
                                        <span x-show="ev.tipo"
                                              class="text-xs font-medium text-udg-blue/70"
                                              x-text="ev.tipo"
                                        ></span>
                                        <span x-show="ev.tipo && ev.organizador" class="text-xs text-gray-300">|</span>
                                        <span x-show="ev.organizador"
                                              class="text-xs text-gray-500 truncate"
                                              x-text="ev.organizador"
                                        ></span>
                                    </div>
                                </div>
                                {{-- Chevron indicador --}}
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5 transition-transform duration-200"
                                     :class="expandedIdx === idx && 'rotate-180'"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                            </div>
                        </button>

                        {{-- Contenido expandido (detalles completos) --}}
                        <div x-show="expandedIdx === idx"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             x-cloak
                             class="border-t border-gray-200 px-4 py-3 space-y-3"
                        >
                            {{-- Institucion --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-0.5">Institucion</p>
                                <p class="text-sm text-gray-900" x-text="ev.institucion || 'Sin institucion'"></p>
                            </div>

                            {{-- Organizador + Administracion --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-0.5">Organizador</p>
                                <p class="text-sm text-gray-900" x-text="ev.organizador || 'Sin organizador'"></p>
                                <p x-show="ev.administracion"
                                   class="text-xs text-gray-400 mt-0.5"
                                   x-text="ev.administracion"
                                ></p>
                            </div>

                            {{-- Tipo de evento --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-0.5">Tipo de evento</p>
                                <p class="text-sm text-gray-900" x-text="ev.tipo || 'Sin tipo'"></p>
                            </div>

                            {{-- Ubicacion --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-0.5">Ubicacion</p>
                                <p class="text-sm text-gray-900" x-text="ev.ubicacion || 'Sin ubicacion registrada'"></p>
                            </div>

                            {{-- Horario --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-0.5">Horario</p>
                                <p x-show="ev.fechaTexto" class="text-sm text-gray-900" x-text="ev.fechaTexto"></p>
                                <p class="text-sm text-gray-600" x-text="ev.horario"></p>
                            </div>

                            {{-- Notas CTA (condicional) --}}
                            <template x-if="ev.notas_cta">
                                <div>
                                    <div class="border-t border-gray-100 mb-2"></div>
                                    <p class="text-xs font-semibold text-gray-500 mb-0.5">Notas CTA</p>
                                    <p class="text-sm text-gray-700 whitespace-pre-line" x-text="ev.notas_cta"></p>
                                </div>
                            </template>

                            {{-- Link al evento (solo si showFooter es true) --}}
                            @if($showFooter)
                                <div class="pt-1">
                                    <a :href="'{{ $eventoBaseUrl }}/' + ev.evento_id"
                                       class="inline-flex items-center gap-1.5 text-xs font-medium text-udg-blue hover:text-udg-blue/80 transition-colors"
                                    >
                                        <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" />
                                        Ver evento completo
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </template>
        </div>

    </div>
</div>
