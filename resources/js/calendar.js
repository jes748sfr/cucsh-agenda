import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // ── Tooltip reutilizable (se crea una sola vez) ──────────────────
    const tooltip = document.createElement('div');
    tooltip.id = 'fc-tooltip';
    document.body.appendChild(tooltip);

    // Iconos SVG inline (Heroicons outline 14x14)
    const iconCalendar = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>';
    const iconBuilding = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/></svg>';

    // Icono reloj (Heroicons outline, 12x12) — usado en pills de timeGrid
    const iconClock = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="fc-tg-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';

    // Formateadores de fecha y hora en español
    const fmtFecha = new Intl.DateTimeFormat('es-MX', {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
    });
    const fmtHora = new Intl.DateTimeFormat('es-MX', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
    });

    // ── Utilidades de color ──────────────────────────────────────────

    /**
     * Convierte un color hexadecimal a una versión clara (tint) para fondo.
     * Mezcla el color con blanco al porcentaje dado (0-1).
     * @param {string} hex - Color hexadecimal (#RRGGBB)
     * @param {number} amount - Intensidad de aclarado (0 = original, 1 = blanco)
     * @returns {string} Color hex aclarado
     */
    function lightenHex(hex, amount) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);

        const lr = Math.round(r + (255 - r) * amount);
        const lg = Math.round(g + (255 - g) * amount);
        const lb = Math.round(b + (255 - b) * amount);

        return '#' + [lr, lg, lb].map(function (c) {
            return c.toString(16).padStart(2, '0');
        }).join('');
    }

    /**
     * Oscurece un color hex para usarlo como texto legible.
     * @param {string} hex - Color hexadecimal
     * @param {number} amount - Intensidad de oscurecimiento (0 = original, 1 = negro)
     * @returns {string} Color hex oscurecido
     */
    function darkenHex(hex, amount) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);

        const dr = Math.round(r * (1 - amount));
        const dg = Math.round(g * (1 - amount));
        const db = Math.round(b * (1 - amount));

        return '#' + [dr, dg, db].map(function (c) {
            return c.toString(16).padStart(2, '0');
        }).join('');
    }

    // ── Posicionamiento del tooltip ──────────────────────────────────

    /**
     * Posiciona el tooltip centrado debajo del elemento pill.
     * Ajusta si se sale del viewport por los bordes.
     * @param {HTMLElement} anchorEl - Elemento pill del evento
     */
    function positionTooltip(anchorEl) {
        const rect = anchorEl.getBoundingClientRect();
        const gap = 8;

        // Posicionar invisible para medir dimensiones
        tooltip.style.left = '0px';
        tooltip.style.top = '0px';
        tooltip.classList.remove('fc-tooltip-visible');

        const tw = tooltip.offsetWidth;
        const th = tooltip.offsetHeight;
        const vw = window.innerWidth;
        const vh = window.innerHeight;

        // Centrar horizontalmente debajo del pill
        let left = rect.left + (rect.width / 2) - (tw / 2);
        let top = rect.bottom + gap;

        // Ajuste horizontal — no salirse por la derecha
        if (left + tw > vw - 8) {
            left = vw - tw - 8;
        }
        // Ajuste horizontal — no salirse por la izquierda
        if (left < 8) {
            left = 8;
        }

        // Si no cabe abajo, mostrar arriba del pill
        if (top + th > vh - 8) {
            top = rect.top - th - gap;
        }

        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';

        // Activar transición de entrada
        requestAnimationFrame(function () {
            tooltip.classList.add('fc-tooltip-visible');
        });
    }

    /**
     * Posiciona el tooltip junto a una celda de timeGrid (intersección día+hora).
     * En timeGridWeek usa las coordenadas del mouse para encontrar la columna
     * del día y posiciona el tooltip a la derecha de esa celda.
     * En timeGridDay usa el posicionamiento centrado estándar.
     * @param {HTMLElement} slotLane - El elemento .fc-timegrid-slot-lane
     * @param {MouseEvent} mouseEvent - Evento del mouse para determinar la columna
     */
    function positionSlotTooltip(slotLane, mouseEvent) {
        var viewType = calendar.view.type;

        // En vista diaria: usar posicionamiento centrado estándar
        if (viewType === 'timeGridDay') {
            positionTooltip(slotLane);
            return;
        }

        // Vista semanal: encontrar la columna del día bajo el cursor
        var colEls = calendarEl.querySelectorAll('.fc-timegrid-col:not(.fc-timegrid-axis)');
        var targetCol = null;

        for (var i = 0; i < colEls.length; i++) {
            var colRect = colEls[i].getBoundingClientRect();
            if (mouseEvent.clientX >= colRect.left && mouseEvent.clientX < colRect.right) {
                targetCol = colEls[i];
                break;
            }
        }

        // Fallback: si no se encontró columna, usar posicionamiento estándar
        if (!targetCol) {
            positionTooltip(slotLane);
            return;
        }

        var colRect = targetCol.getBoundingClientRect();
        var slotRect = slotLane.getBoundingClientRect();
        var gap = 8;

        // Posicionar invisible para medir dimensiones
        tooltip.style.left = '0px';
        tooltip.style.top = '0px';
        tooltip.classList.remove('fc-tooltip-visible');

        var tw = tooltip.offsetWidth;
        var th = tooltip.offsetHeight;
        var vw = window.innerWidth;

        // Posición vertical: centrar verticalmente respecto al slot
        var top = slotRect.top + (slotRect.height / 2) - (th / 2);

        // Posición horizontal: a la derecha de la celda
        var left = colRect.right + gap;

        // Si no cabe a la derecha, mostrar a la izquierda de la celda
        if (left + tw > vw - 8) {
            left = colRect.left - tw - gap;
        }

        // Si tampoco cabe a la izquierda, pegar al borde derecho del viewport
        if (left < 8) {
            left = 8;
        }

        // Ajuste vertical: no salirse por arriba ni por abajo
        var vh = window.innerHeight;
        if (top + th > vh - 8) {
            top = vh - th - 8;
        }
        if (top < 8) {
            top = 8;
        }

        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';

        // Activar transición de entrada
        requestAnimationFrame(function () {
            tooltip.classList.add('fc-tooltip-visible');
        });
    }

    // ── Calendario FullCalendar ──────────────────────────────────────

    // Leer vista inicial desde parametro URL ?vista= (si existe y es valida)
    const vistaValidas = ['dayGridMonth', 'timeGridWeek', 'timeGridDay', 'listWeek'];
    const urlParams = new URLSearchParams(window.location.search);
    const vistaParam = urlParams.get('vista');
    const initialViewFromUrl = vistaValidas.includes(vistaParam) ? vistaParam : 'dayGridMonth';

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],

        // Vistas — restaurar la vista previa si viene en la URL
        initialView: initialViewFromUrl,
        headerToolbar: false,

        // Localización
        locale: esLocale,
        firstDay: 1,
        noEventsText: 'No hay eventos para mostrar',

        // Formato de hora en pills: "10:00 AM"
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short',
        },

        // ── Contenido custom para pills en timeGrid ──────────────
        eventContent: function (arg) {
            var viewType = arg.view.type;

            // Solo personalizar pills en vistas timeGrid (semanal y diaria).
            // Retornar `true` indica a FullCalendar que use su renderizado por defecto.
            // (Retornar `undefined` causa que FC renderice un elemento vacio.)
            if (viewType !== 'timeGridWeek' && viewType !== 'timeGridDay') {
                return true;
            }

            var ev = arg.event;
            var props = ev.extendedProps;
            var timeText = arg.timeText || '';
            var title = ev.title || '';
            var organizador = props.organizador || '';

            // Construir HTML del pill con tres niveles de jerarquia
            var html = '<div class="fc-tg-pill">';

            // Linea 1: hora con icono de reloj
            html += '<div class="fc-tg-time">'
                  + iconClock
                  + '<span>' + escapeHtml(timeText) + '</span>'
                  + '</div>';

            // Linea 2: titulo del evento (prominente)
            html += '<div class="fc-tg-title">' + escapeHtml(title) + '</div>';

            // Linea 3: organizador como subtitulo
            if (organizador) {
                html += '<div class="fc-tg-org">' + escapeHtml(organizador) + '</div>';
            }

            html += '</div>';

            return { html: html };
        },

        // Eventos desde API — lee filtros del componente Alpine
        events: function (fetchInfo, successCallback, failureCallback) {
            var params = new URLSearchParams();
            params.set('start', fetchInfo.startStr);
            params.set('end', fetchInfo.endStr);

            // Leer filtros del componente Alpine
            if (window.__calendarFilters) {
                var apiParams = window.__calendarFilters.getApiParams();
                Object.keys(apiParams).forEach(function (key) {
                    var value = apiParams[key];
                    if (Array.isArray(value)) {
                        // Serializar arrays como parametros repetidos: key=1&key=2
                        value.forEach(function (v) {
                            params.append(key, v);
                        });
                    } else {
                        params.set(key, value);
                    }
                });
            }

            fetch('/api/events?' + params.toString())
                .then(function (response) { return response.json(); })
                .then(function (data) { successCallback(data); })
                .catch(function () { failureCallback(); });
        },

        // TimeGrid (vista semanal/diaria)
        slotMinTime: '07:00:00',
        slotMaxTime: '22:00:00',
        slotDuration: '00:30:00',
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short',
            omitZeroMinute: false,
        },
        nowIndicator: true,
        allDaySlot: false,

        // DayGrid (vista mensual)
        dayMaxEventRows: 2,

        // Altura y distribución de filas
        height: '100%',
        expandRows: true,
        fixedWeekCount: false,
        eventDisplay: 'block',

        // ── Badge de fecha: despachar info al componente Alpine ──
        datesSet: function (info) {
            var hoy = new Date();
            var mesLogico = info.view.currentStart; // día 1 del mes/semana lógica
            var ref;

            if (info.view.type === 'dayGridMonth') {
                // En vista mensual: mostrar hoy si estamos viendo el mes actual, sino día 1
                var mismoMes = hoy.getFullYear() === mesLogico.getFullYear()
                            && hoy.getMonth() === mesLogico.getMonth();
                ref = mismoMes ? hoy : mesLogico;
            } else {
                // En semana/día: mostrar hoy si está en el rango visible, sino el inicio
                ref = (hoy >= info.start && hoy < info.end) ? hoy : info.start;
            }

            var fmtMesCorto = new Intl.DateTimeFormat('es-MX', { month: 'short' });
            var fmtMesLargo = new Intl.DateTimeFormat('es-MX', { month: 'long', year: 'numeric' });
            var fmtDiaSemana = new Intl.DateTimeFormat('es-MX', { weekday: 'long' });

            // Fecha ISO para el botón "Nuevo evento" (YYYY-MM-DD)
            var yyyy = ref.getFullYear();
            var mm = String(ref.getMonth() + 1).padStart(2, '0');
            var dd = String(ref.getDate()).padStart(2, '0');

            window.dispatchEvent(new CustomEvent('calendar-date-change', {
                detail: {
                    mesCorto: fmtMesCorto.format(ref).replace('.', '').toUpperCase(),
                    diaNum: ref.getDate(),
                    titulo: fmtMesLargo.format(ref),
                    diaSemana: fmtDiaSemana.format(ref),
                    viewType: info.view.type,
                    fechaISO: yyyy + '-' + mm + '-' + dd,
                }
            }));
        },

        // ── Renderizado de pills con color dinámico ────────────
        // Color por defecto: #7FBCD2 — será reemplazado por el color
        // elegido por el usuario al registrar el evento
        eventDidMount: function (info) {
            var el = info.el;
            var pillColor = info.event.backgroundColor || '#7FBCD2';

            // Parsear hex a RGB para fondo con transparencia
            var hex = pillColor.replace('#', '');
            if (hex.length === 3) {
                hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
            }
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);

            // Aplicar color via CSS custom properties (accesibles desde CSS)
            el.style.setProperty('--pill-color', pillColor);
            el.style.setProperty('--pill-rgb', r + ', ' + g + ', ' + b);

            // Estilos inline comunes a todas las vistas
            el.style.backgroundColor = 'rgba(' + r + ', ' + g + ', ' + b + ', 0.18)';
            el.style.borderLeft = '3px solid ' + pillColor;
            el.style.borderRight = 'none';
            el.style.borderTop = 'none';
            el.style.borderBottom = 'none';
            el.style.color = '#1f2937';
        },

        // ── Tooltip en hover (solo vista mensual) ──────────────
        eventMouseEnter: function (info) {
            if (info.view.type !== 'dayGridMonth') return;

            var ev = info.event;
            var props = ev.extendedProps;

            var fecha = ev.start ? fmtFecha.format(ev.start) : '';
            var horaStart = ev.start ? fmtHora.format(ev.start) : '';
            var horaEnd = ev.end ? fmtHora.format(ev.end) : '';
            var rango = horaEnd ? horaStart + ' \u2013 ' + horaEnd : horaStart;

            var html = '<div class="fc-tooltip-title">' + escapeHtml(ev.title) + '</div>';

            if (props.tipo) {
                html += '<div class="fc-tooltip-type">' + escapeHtml(props.tipo) + '</div>';
            }

            html += '<div class="fc-tooltip-row">' + iconCalendar + '<span>' + escapeHtml(fecha) + ' &middot; ' + escapeHtml(rango) + '</span></div>';

            if (props.institucion) {
                html += '<div class="fc-tooltip-row">' + iconBuilding + '<span>' + escapeHtml(props.institucion) + '</span></div>';
            }

            tooltip.innerHTML = html;
            positionTooltip(info.el);
        },

        eventMouseLeave: function () {
            tooltip.classList.remove('fc-tooltip-visible');
        },

        // Click en celda/slot → crear evento o cambiar vista
        dateClick: function (info) {
            if (info.view.type === 'dayGridMonth') {
                // En vista mensual: navegar a vista diaria
                calendar.changeView('timeGridDay', info.dateStr);
            } else if (info.view.type === 'timeGridWeek' || info.view.type === 'timeGridDay') {
                // En vistas timeGrid: redirigir a crear evento con fecha y hora pre-llenadas
                var fecha = info.dateStr.substring(0, 10); // YYYY-MM-DD
                var hora = info.date.toTimeString().substring(0, 5); // HH:mm
                var vista = calendar.view.type;
                window.location.href = '/eventos/create?fecha=' + fecha + '&hora_inicio=' + hora + '&from=dashboard&vista=' + vista;
            }
        },

        // Click en evento → panel lateral (timeGridDay desktop) o navegar a show
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            tooltip.classList.remove('fc-tooltip-visible');

            var ev = info.event;
            var props = ev.extendedProps;
            var eventoId = props.evento_id;
            var isTimeGridDay = info.view.type === 'timeGridDay';
            var isDesktop = window.innerWidth >= 1024;

            if (isTimeGridDay && isDesktop) {
                // Formatear fecha y horario para el panel
                var fechaTexto = ev.start
                    ? fmtFecha.format(ev.start)
                    : '';
                var horaStart = ev.start ? fmtHora.format(ev.start) : '';
                var horaEnd = ev.end ? fmtHora.format(ev.end) : '';
                var horarioTexto = horaEnd
                    ? horaStart + ' \u2013 ' + horaEnd
                    : horaStart;

                window.dispatchEvent(new CustomEvent('show-event-panel', {
                    detail: {
                        evento_id: eventoId,
                        title: ev.title,
                        tipo: props.tipo || '',
                        institucion: props.institucion || '',
                        organizador: props.organizador || '',
                        ubicacion: props.ubicacion || '',
                        notas_cta: props.notas_cta || '',
                        fechaTexto: fechaTexto,
                        horarioTexto: horarioTexto,
                    }
                }));
            } else {
                window.location.href = '/eventos/' + eventoId;
            }
        },
    });

    calendar.render();

    // ── Tooltip en slots de timeGrid — "Crear evento a las HH:mm" ─────
    // Usa delegación de eventos en el contenedor del calendario para
    // detectar hover sobre .fc-timegrid-slot-lane y mostrar el tooltip
    // con la hora correspondiente extraída del slot-label adyacente.

    // Icono "plus" SVG (Heroicons outline, se usa en el tooltip de slot)
    var iconPlus = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px;flex-shrink:0;color:#202945"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>';

    var slotTooltipTimeout = null;
    var currentSlotLane = null;

    // Usar mouseover/mouseout en lugar de mouseenter/mouseleave porque
    // mouseenter NO burbujea y no funciona con delegacion de eventos.
    // mouseover/mouseout SI burbujean y permiten detectar hover en hijos.
    calendarEl.addEventListener('mouseover', function (e) {
        var lane = e.target.closest('.fc-timegrid-slot-lane');
        if (!lane) return;

        // Evitar re-procesar si seguimos en el mismo slot
        if (lane === currentSlotLane) return;
        currentSlotLane = lane;

        // Solo mostrar en vistas timeGrid
        var viewType = calendar.view.type;
        if (viewType !== 'timeGridWeek' && viewType !== 'timeGridDay') return;

        // Obtener la hora desde el atributo data-time del <tr> padre
        var row = lane.closest('tr');
        if (!row) return;
        var dataTime = row.getAttribute('data-time');
        if (!dataTime) {
            // Fallback: buscar data-time en el propio <td>
            dataTime = lane.getAttribute('data-time');
        }
        if (!dataTime) return;

        // Formatear hora legible: "10:00" → "10:00 AM"
        var parts = dataTime.split(':');
        var h = parseInt(parts[0], 10);
        var m = parts[1];
        var meridiem = h >= 12 ? 'PM' : 'AM';
        var h12 = h === 0 ? 12 : (h > 12 ? h - 12 : h);
        var horaTexto = h12 + ':' + m + ' ' + meridiem;

        // Limpiar timeout previo de ocultamiento
        if (slotTooltipTimeout) {
            clearTimeout(slotTooltipTimeout);
            slotTooltipTimeout = null;
        }

        tooltip.innerHTML = '<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#202945;font-weight:500;white-space:nowrap">'
            + iconPlus
            + '<span>Crear evento a las ' + escapeHtml(horaTexto) + '</span>'
            + '</div>';
        positionSlotTooltip(lane, e);
    });

    // Reposicionar tooltip al moverse entre columnas dentro del mismo slot.
    // Rastrear la columna actual para evitar reposicionar innecesariamente.
    var currentSlotCol = null;

    calendarEl.addEventListener('mousemove', function (e) {
        if (!currentSlotLane) return;
        var viewType = calendar.view.type;
        if (viewType !== 'timeGridWeek') return;

        // Solo reposicionar si el tooltip está visible
        if (!tooltip.classList.contains('fc-tooltip-visible')) return;

        // Encontrar la columna bajo el cursor
        var colEls = calendarEl.querySelectorAll('.fc-timegrid-col:not(.fc-timegrid-axis)');
        var hoveredCol = null;
        for (var i = 0; i < colEls.length; i++) {
            var colRect = colEls[i].getBoundingClientRect();
            if (e.clientX >= colRect.left && e.clientX < colRect.right) {
                hoveredCol = colEls[i];
                break;
            }
        }

        // Solo reposicionar si cambió la columna
        if (hoveredCol && hoveredCol !== currentSlotCol) {
            currentSlotCol = hoveredCol;
            positionSlotTooltip(currentSlotLane, e);
        }
    });

    calendarEl.addEventListener('mouseout', function (e) {
        // Solo procesar si estamos rastreando un slot lane (tooltip de timeGrid).
        // Sin esta guarda, el mouseout que burbujea desde pills de dayGridMonth
        // oculta el tooltip de evento despues de ~80ms.
        if (!currentSlotLane) return;

        // Verificar que realmente salimos del slot lane actual
        var related = e.relatedTarget;
        if (related && currentSlotLane.contains(related)) return;

        currentSlotLane = null;
        currentSlotCol = null;

        // Retrasar para evitar parpadeo entre slots adyacentes
        slotTooltipTimeout = setTimeout(function () {
            tooltip.classList.remove('fc-tooltip-visible');
        }, 80);
    });

    // Escuchar evento custom de refetch disparado por los tabs Alpine
    window.addEventListener('calendar-refetch', function () {
        calendar.refetchEvents();
    });

    // Navegación custom: prev / next / today (botones Blade)
    window.addEventListener('calendar-prev', function () {
        calendar.prev();
    });
    window.addEventListener('calendar-next', function () {
        calendar.next();
    });
    window.addEventListener('calendar-today', function () {
        calendar.today();
    });
    window.addEventListener('calendar-change-view', function (e) {
        calendar.changeView(e.detail.view);
    });

    // Escapar HTML para prevenir XSS en el tooltip
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});
