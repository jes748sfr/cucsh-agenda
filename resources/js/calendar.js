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

    // ── Popover semanal (interactivo, con botón cerrar y link) ─────
    const weekPopover = document.createElement('div');
    weekPopover.id = 'fc-week-popover';
    weekPopover.style.display = 'none';
    document.body.appendChild(weekPopover);

    // ID del evento actualmente mostrado en el popover (para toggle)
    var weekPopoverEventId = null;

    // Iconos SVG inline (Heroicons outline 14x14)
    const iconCalendar = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>';
    const iconBuilding = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/></svg>';

    // Icono reloj (Heroicons outline, 12x12) — usado en pills de timeGrid
    const iconClock = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="fc-tg-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';

    // Icono alerta (Heroicons outline) — usado para eventos importantes
    const iconAlert = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>';

    // Color que identifica eventos importantes
    const IMPORTANT_COLOR = '#FF6868';

    /**
     * Verifica si un evento es "importante" por su color.
     * @param {Object} ev - Evento de FullCalendar o raw event object
     * @returns {boolean}
     */
    function isImportant(ev) {
        var color = ev.backgroundColor || (ev.extendedProps && ev.extendedProps.color) || '';
        return color.toUpperCase() === IMPORTANT_COLOR.toUpperCase();
    }

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

    // ── Popover semanal: mostrar, posicionar y cerrar ───────────────

    /**
     * Muestra el popover de detalle en la vista semanal (timeGridWeek).
     * Se posiciona a la izquierda de la columna del día del evento,
     * excepto para la primera columna (lunes) donde se posiciona a la derecha.
     * @param {Object} ev - Objeto evento de FullCalendar
     * @param {HTMLElement} eventEl - Elemento DOM del pill clickeado
     */
    function showWeekPopover(ev, eventEl) {
        weekPopoverEventId = ev.id;
        var props = ev.extendedProps;

        // Formatear horario
        var horaStart = ev.start ? fmtHora.format(ev.start) : '';
        var horaEnd = ev.end ? fmtHora.format(ev.end) : '';
        var horario = horaEnd ? horaStart + ' \u2013 ' + horaEnd : horaStart;
        var fechaTexto = ev.start ? fmtFecha.format(ev.start) : '';

        // Construir HTML del popover
        var html = '';

        // Cabecera: titulo + botón cerrar
        html += '<div class="fc-wpop-header">';
        html += '<h4 class="fc-wpop-title">' + escapeHtml(ev.title) + '</h4>';
        html += '<button type="button" class="fc-wpop-close" aria-label="Cerrar">';
        html += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>';
        html += '</button>';
        html += '</div>';

        // Campos de detalle
        html += '<div class="fc-wpop-body">';

        if (props.tipo) {
            html += '<div class="fc-wpop-field">';
            html += '<span class="fc-wpop-label">Tipo de evento</span>';
            html += '<span class="fc-wpop-value">' + escapeHtml(props.tipo) + '</span>';
            html += '</div>';
        }

        html += '<div class="fc-wpop-field">';
        html += '<span class="fc-wpop-label">Institucion</span>';
        html += '<span class="fc-wpop-value">' + escapeHtml(props.institucion || 'Sin institucion') + '</span>';
        html += '</div>';

        html += '<div class="fc-wpop-field">';
        html += '<span class="fc-wpop-label">Organizador</span>';
        html += '<span class="fc-wpop-value">' + escapeHtml(props.organizador || 'Sin organizador') + '</span>';
        if (props.administracion) {
            html += '<span class="fc-wpop-sub">' + escapeHtml(props.administracion) + '</span>';
        }
        html += '</div>';

        if (props.ubicacion) {
            html += '<div class="fc-wpop-field">';
            html += '<span class="fc-wpop-label">Ubicacion</span>';
            html += '<span class="fc-wpop-value">' + escapeHtml(props.ubicacion) + '</span>';
            html += '</div>';
        }

        html += '<div class="fc-wpop-field">';
        html += '<span class="fc-wpop-label">Horario</span>';
        html += '<span class="fc-wpop-value">' + escapeHtml(fechaTexto) + '</span>';
        html += '<span class="fc-wpop-sub">' + escapeHtml(horario) + '</span>';
        html += '</div>';

        if (props.notas_cta) {
            html += '<div class="fc-wpop-divider"></div>';
            html += '<div class="fc-wpop-field">';
            html += '<span class="fc-wpop-label">Notas CTA</span>';
            html += '<span class="fc-wpop-value fc-wpop-notes">' + escapeHtml(props.notas_cta) + '</span>';
            html += '</div>';
        }

        // Indicador de evento importante
        if (isImportant(ev)) {
            html += '<div class="fc-wpop-divider"></div>';
            html += '<div class="fc-wpop-important">' + iconAlert + '<span>Evento importante</span></div>';
        }

        html += '</div>';

        // Footer: link a vista completa (solo en modo autenticado)
        if (!window.__calendarPublicMode) {
            var eventoId = props.evento_id;
            html += '<div class="fc-wpop-footer">';
            html += '<a href="/eventos/' + eventoId + '" class="fc-wpop-link">';
            html += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>';
            html += '<span>Ver evento completo</span>';
            html += '</a>';
            html += '</div>';
        }

        weekPopover.innerHTML = html;
        weekPopover.style.display = 'block';

        // Posicionar respecto a la columna del día
        positionWeekPopover(eventEl);

        // Bind cierre por botón X (cada vez que se abre)
        var closeBtn = weekPopover.querySelector('.fc-wpop-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeWeekPopover);
        }
    }

    /**
     * Posiciona el popover semanal anclado a la izquierda de la columna del día.
     * Para la primera columna (lunes), lo ancla a la derecha.
     * @param {HTMLElement} eventEl - Elemento DOM del pill
     */
    function positionWeekPopover(eventEl) {
        var gap = 8;

        // Encontrar la columna del día que contiene el evento
        var colEl = eventEl.closest('.fc-timegrid-col');
        if (!colEl) {
            // Fallback: posicionar junto al pill
            colEl = eventEl;
        }

        // Detectar indice de columna para saber si es la primera (lunes)
        var colEls = calendarEl.querySelectorAll('.fc-timegrid-col:not(.fc-timegrid-axis)');
        var colIndex = -1;
        for (var i = 0; i < colEls.length; i++) {
            if (colEls[i] === colEl) {
                colIndex = i;
                break;
            }
        }

        var colRect = colEl.getBoundingClientRect();
        var eventRect = eventEl.getBoundingClientRect();

        // Medir el popover
        var pw = weekPopover.offsetWidth;
        var ph = weekPopover.offsetHeight;
        var vw = window.innerWidth;
        var vh = window.innerHeight;

        var left, top;

        if (colIndex === 0) {
            // Primera columna (lunes): anclar a la derecha de la columna
            left = colRect.right + gap;
        } else {
            // Otras columnas: anclar a la izquierda de la columna
            left = colRect.left - pw - gap;
        }

        // Vertical: alinear borde superior del popover con borde superior del pill
        top = eventRect.top;

        // Ajustes de viewport
        if (left + pw > vw - 8) {
            left = colRect.left - pw - gap; // Fallback izquierda
        }
        if (left < 8) {
            left = colRect.right + gap; // Fallback derecha
        }
        if (top + ph > vh - 8) {
            top = vh - ph - 8;
        }
        if (top < 8) {
            top = 8;
        }

        weekPopover.style.left = left + 'px';
        weekPopover.style.top = top + 'px';

        // Activar transición de entrada
        requestAnimationFrame(function () {
            weekPopover.classList.add('fc-wpop-visible');
        });
    }

    /**
     * Cierra el popover semanal.
     */
    function closeWeekPopover() {
        weekPopoverEventId = null;
        weekPopover.classList.remove('fc-wpop-visible');
        // Limpiar listeners del acordeon de grupo (si los hay)
        cleanupAccordionListeners();
        // Ocultar tras la transicion
        setTimeout(function () {
            weekPopover.style.display = 'none';
        }, 180);
    }

    // Cierre por click fuera del popover.
    // Ignorar clicks en pills de FullCalendar — el eventClick se encarga del toggle.
    document.addEventListener('mousedown', function (e) {
        if (weekPopover.style.display === 'none') return;
        if (weekPopover.contains(e.target)) return;
        if (e.target.closest('.fc-event')) return;
        closeWeekPopover();
    });

    // Cierre por tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && weekPopover.style.display !== 'none') {
            closeWeekPopover();
        }
    });

    // ── Popover semanal para grupos: acordeon de eventos ──────────

    // Listeners activos del acordeon (para limpiar al cerrar)
    var weekGroupAccordionListeners = [];

    /**
     * Muestra el popover de grupo en la vista semanal (timeGridWeek).
     * Genera un acordeon con N items colapsables, uno por evento del grupo.
     * Solo un item expandido a la vez (el primero por defecto).
     * @param {Object} ev - Pseudo-evento grupo de FullCalendar
     * @param {HTMLElement} eventEl - Elemento DOM del pill clickeado
     */
    function showWeekGroupPopover(ev, eventEl) {
        weekPopoverEventId = ev.id;
        var props = ev.extendedProps;
        var groupedEvents = props.groupedEvents || [];
        var count = props.groupCount || groupedEvents.length;

        // Limpiar listeners previos del acordeon
        cleanupAccordionListeners();

        // Formatear rango horario del grupo
        var groupStart = ev.start ? fmtHora.format(ev.start) : '';
        var groupEnd = ev.end ? fmtHora.format(ev.end) : '';
        var rangoHorario = groupEnd ? groupStart + ' \u2013 ' + groupEnd : groupStart;

        // Chevron SVG para items del acordeon
        var chevronSvg = '<svg class="fc-wpop-accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>';

        // Construir HTML del popover
        var html = '';

        // Contar eventos importantes en el grupo
        var groupImportantCount = 0;
        for (var ic = 0; ic < groupedEvents.length; ic++) {
            if (isImportant(groupedEvents[ic])) groupImportantCount++;
        }

        // Cabecera: rango horario + count + boton cerrar
        html += '<div class="fc-wpop-header">';
        html += '<div>';
        html += '<h4 class="fc-wpop-title">+' + count + ' eventos</h4>';
        html += '<div style="display:flex;align-items:center;gap:10px;margin-top:2px">';
        html += '<span class="fc-wpop-sub">' + escapeHtml(rangoHorario) + '</span>';
        if (groupImportantCount > 0) {
            html += '<span class="fc-wpop-important">' + iconAlert + '<span>' + groupImportantCount + ' importante' + (groupImportantCount > 1 ? 's' : '') + '</span></span>';
        }
        html += '</div>';
        html += '</div>';
        html += '<button type="button" class="fc-wpop-close" aria-label="Cerrar">';
        html += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>';
        html += '</button>';
        html += '</div>';

        // Acordeon: lista de items colapsables
        html += '<div class="fc-wpop-accordion">';

        for (var i = 0; i < groupedEvents.length; i++) {
            var raw = groupedEvents[i];
            var rawProps = raw.extendedProps || {};
            var start = raw.start ? new Date(raw.start) : null;
            var end = raw.end ? new Date(raw.end) : null;
            var horaStart = start ? fmtHora.format(start) : '';
            var horaEnd = end ? fmtHora.format(end) : '';
            var horario = horaEnd ? horaStart + ' \u2013 ' + horaEnd : horaStart;
            var pillColor = raw.backgroundColor || '#7FBCD2';
            var isFirst = (i === 0);

            html += '<div class="fc-wpop-accordion-item' + (isFirst ? ' fc-wpop-accordion-item--open' : '') + '" data-accordion-idx="' + i + '">';

            // Cabecera clickable del item
            html += '<button type="button" class="fc-wpop-accordion-header" data-accordion-toggle="' + i + '">';
            html += '<div class="fc-wpop-accordion-bar" style="background-color:' + escapeHtml(pillColor) + '"></div>';
            html += '<div class="fc-wpop-accordion-header-text">';
            var rawIsImportant = isImportant(raw);
            if (rawIsImportant) {
                html += '<div class="fc-wpop-accordion-title-wrap">';
                html += '<span class="fc-wpop-accordion-title">' + escapeHtml(raw.title || '') + '</span>';
                html += '<span class="fc-important-dot"></span>';
                html += '</div>';
            } else {
                html += '<span class="fc-wpop-accordion-title">' + escapeHtml(raw.title || '') + '</span>';
            }
            html += '<span class="fc-wpop-accordion-time">' + escapeHtml(horario) + '</span>';
            html += '</div>';
            html += chevronSvg;
            html += '</button>';

            // Cuerpo expandible con campos de detalle
            html += '<div class="fc-wpop-accordion-body"' + (isFirst ? ' style="max-height:500px"' : '') + '>';
            html += '<div class="fc-wpop-accordion-body-inner">';

            if (rawProps.tipo) {
                html += '<div class="fc-wpop-field">';
                html += '<span class="fc-wpop-label">Tipo de evento</span>';
                html += '<span class="fc-wpop-value">' + escapeHtml(rawProps.tipo) + '</span>';
                html += '</div>';
            }

            html += '<div class="fc-wpop-field">';
            html += '<span class="fc-wpop-label">Institucion</span>';
            html += '<span class="fc-wpop-value">' + escapeHtml(rawProps.institucion || 'Sin institucion') + '</span>';
            html += '</div>';

            html += '<div class="fc-wpop-field">';
            html += '<span class="fc-wpop-label">Organizador</span>';
            html += '<span class="fc-wpop-value">' + escapeHtml(rawProps.organizador || 'Sin organizador') + '</span>';
            if (rawProps.administracion) {
                html += '<span class="fc-wpop-sub">' + escapeHtml(rawProps.administracion) + '</span>';
            }
            html += '</div>';

            if (rawProps.ubicacion) {
                html += '<div class="fc-wpop-field">';
                html += '<span class="fc-wpop-label">Ubicacion</span>';
                html += '<span class="fc-wpop-value">' + escapeHtml(rawProps.ubicacion) + '</span>';
                html += '</div>';
            }

            if (rawProps.notas_cta) {
                html += '<div class="fc-wpop-divider"></div>';
                html += '<div class="fc-wpop-field">';
                html += '<span class="fc-wpop-label">Notas CTA</span>';
                html += '<span class="fc-wpop-value fc-wpop-notes">' + escapeHtml(rawProps.notas_cta) + '</span>';
                html += '</div>';
            }

            // Link a vista completa (solo modo autenticado)
            if (!window.__calendarPublicMode && rawProps.evento_id) {
                html += '<div class="fc-wpop-accordion-link">';
                html += '<a href="/eventos/' + rawProps.evento_id + '" class="fc-wpop-link">';
                html += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>';
                html += '<span>Ver evento</span>';
                html += '</a>';
                html += '</div>';
            }

            html += '</div>'; // .fc-wpop-accordion-body-inner
            html += '</div>'; // .fc-wpop-accordion-body
            html += '</div>'; // .fc-wpop-accordion-item
        }

        html += '</div>'; // .fc-wpop-accordion

        weekPopover.innerHTML = html;
        weekPopover.style.display = 'block';

        // Posicionar respecto a la columna del dia
        positionWeekPopover(eventEl);

        // Bind cierre por boton X
        var closeBtn = weekPopover.querySelector('.fc-wpop-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeWeekPopover);
        }

        // Bind toggle de items del acordeon
        var toggleBtns = weekPopover.querySelectorAll('[data-accordion-toggle]');
        for (var t = 0; t < toggleBtns.length; t++) {
            var handler = (function (btn) {
                return function () {
                    var idx = parseInt(btn.getAttribute('data-accordion-toggle'), 10);
                    toggleWeekAccordionItem(idx);
                };
            })(toggleBtns[t]);

            toggleBtns[t].addEventListener('click', handler);
            weekGroupAccordionListeners.push({ el: toggleBtns[t], handler: handler });
        }
    }

    /**
     * Expande/colapsa un item del acordeon del popover semanal.
     * Solo un item abierto a la vez.
     * @param {number} idx - Indice del item a alternar
     */
    function toggleWeekAccordionItem(idx) {
        var items = weekPopover.querySelectorAll('.fc-wpop-accordion-item');
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            var itemIdx = parseInt(item.getAttribute('data-accordion-idx'), 10);
            var body = item.querySelector('.fc-wpop-accordion-body');
            if (!body) continue;

            if (itemIdx === idx) {
                // Toggle: si ya esta abierto, cerrarlo; si no, abrirlo
                var isOpen = item.classList.contains('fc-wpop-accordion-item--open');
                if (isOpen) {
                    item.classList.remove('fc-wpop-accordion-item--open');
                    body.style.maxHeight = '0';
                } else {
                    item.classList.add('fc-wpop-accordion-item--open');
                    body.style.maxHeight = body.scrollHeight + 'px';
                }
            } else {
                // Cerrar los demas
                item.classList.remove('fc-wpop-accordion-item--open');
                body.style.maxHeight = '0';
            }
        }
    }

    /**
     * Limpia los event listeners del acordeon del popover de grupo.
     */
    function cleanupAccordionListeners() {
        for (var i = 0; i < weekGroupAccordionListeners.length; i++) {
            var entry = weekGroupAccordionListeners[i];
            entry.el.removeEventListener('click', entry.handler);
        }
        weekGroupAccordionListeners = [];
    }

    // ── Agrupamiento de eventos superpuestos (timeGrid) ─────────────

    /**
     * Agrupa eventos superpuestos para vistas timeGrid.
     * En timeGridWeek particiona por día antes de agrupar para evitar
     * que eventos de días distintos con horarios iguales se fusionen.
     * @param {Array} events - Eventos con start/end como strings ISO
     * @param {string} viewType - Tipo de vista ('timeGridDay'|'timeGridWeek')
     * @returns {Array} Eventos procesados (pseudo-eventos grupo + individuales)
     */
    function groupOverlappingEvents(events, viewType) {
        if (!events || events.length === 0) return events;

        // timeGridWeek: particionar por día antes de agrupar
        if (viewType === 'timeGridWeek') {
            var byDay = {};
            for (var i = 0; i < events.length; i++) {
                var dayKey = (events[i].start || '').substring(0, 10);
                if (!byDay[dayKey]) byDay[dayKey] = [];
                byDay[dayKey].push(events[i]);
            }
            var result = [];
            var dayKeys = Object.keys(byDay).sort();
            for (var d = 0; d < dayKeys.length; d++) {
                var dayResult = groupOverlappingInDay(byDay[dayKeys[d]], dayKeys[d]);
                for (var j = 0; j < dayResult.length; j++) {
                    result.push(dayResult[j]);
                }
            }
            return result;
        }

        // timeGridDay: todos los eventos en el mismo día
        return groupOverlappingInDay(events, 'day');
    }

    /**
     * Agrupa eventos solapados dentro de un mismo día.
     * Usa unión transitiva: si A↔B y B↔C, los tres forman un grupo.
     * @param {Array} events - Eventos del mismo día
     * @param {string} dayKey - Clave de día para IDs únicos de grupo
     * @returns {Array} Eventos procesados
     */
    function groupOverlappingInDay(events, dayKey) {
        if (!events || events.length === 0) return events;

        // Parsear y ordenar por hora de inicio
        var parsed = events.map(function (ev) {
            return {
                original: ev,
                start: new Date(ev.start).getTime(),
                end: new Date(ev.end || ev.start).getTime(),
            };
        });
        parsed.sort(function (a, b) { return a.start - b.start; });

        // Agrupar por solapamiento transitivo
        var groups = [];
        var currentGroup = [parsed[0]];
        var groupEnd = parsed[0].end;

        for (var i = 1; i < parsed.length; i++) {
            var item = parsed[i];
            if (item.start < groupEnd) {
                // Se solapa con el grupo actual
                currentGroup.push(item);
                if (item.end > groupEnd) groupEnd = item.end;
            } else {
                // No se solapa — guardar grupo anterior, empezar uno nuevo
                groups.push(currentGroup);
                currentGroup = [item];
                groupEnd = item.end;
            }
        }
        groups.push(currentGroup);

        // Construir array final
        var result = [];
        for (var g = 0; g < groups.length; g++) {
            var group = groups[g];
            if (group.length === 1) {
                // Evento individual — pasar tal cual
                result.push(group[0].original);
            } else {
                // Grupo de 2+ eventos — crear pseudo-evento
                var minStart = group[0].start;
                var maxEnd = group[0].end;
                for (var j = 1; j < group.length; j++) {
                    if (group[j].start < minStart) minStart = group[j].start;
                    if (group[j].end > maxEnd) maxEnd = group[j].end;
                }

                var groupedOriginals = group.map(function (item) {
                    return item.original;
                });

                result.push({
                    id: 'group-' + dayKey + '-' + g,
                    title: '+' + group.length + ' eventos agendados',
                    start: new Date(minStart).toISOString(),
                    end: new Date(maxEnd).toISOString(),
                    extendedProps: {
                        isGroup: true,
                        groupCount: group.length,
                        groupedEvents: groupedOriginals,
                    }
                });
            }
        }

        return result;
    }

    // ── Calendario FullCalendar ──────────────────────────────────────

    // Flag para evitar loop infinito: refetchEvents() dispara datesSet,
    // que a su vez llamaria refetchEvents() de nuevo. Con este flag,
    // el segundo datesSet detecta que fue provocado por un refetch y no repite.
    var isRefetching = false;
    var previousViewType = null;

    // Cache de datos crudos de la API (sin agrupar) para re-agrupar
    // al cambiar de vista sin hacer un nuevo fetch HTTP.
    // Elimina la race condition: el regrouping es síncrono desde cache.
    var rawEventsCache = null;
    var rawEventsCacheKey = '';

    // Leer vista inicial desde parametro URL ?vista= (si existe y es valida)
    const vistaValidas = ['dayGridMonth', 'timeGridWeek', 'timeGridDay', 'listWeek'];
    const urlParams = new URLSearchParams(window.location.search);
    const vistaParam = urlParams.get('vista');
    const initialViewFromUrl = vistaValidas.includes(vistaParam) ? vistaParam : 'dayGridMonth';
    const fechaParam = urlParams.get('fecha');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],

        // Vistas — restaurar la vista previa si viene en la URL
        initialView: initialViewFromUrl,
        initialDate: fechaParam || undefined,
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

            // ── Pill de grupo (eventos superpuestos) ──
            if (props.isGroup) {
                var count = props.groupCount || 0;
                var groupedEvents = props.groupedEvents || [];

                // Contar eventos importantes en el grupo
                var importantCount = 0;
                for (var gi = 0; gi < groupedEvents.length; gi++) {
                    if (isImportant(groupedEvents[gi])) importantCount++;
                }

                // Formatear rango horario del grupo
                var groupStart = ev.start ? fmtHora.format(ev.start) : '';
                var groupEnd = ev.end ? fmtHora.format(ev.end) : '';
                var rangoHorario = groupEnd ? groupStart + ' \u2013 ' + groupEnd : groupStart;

                if (viewType === 'timeGridWeek') {
                    // ── Pill compacto para vista semanal (2-3 lineas max) ──
                    var html = '<div class="fc-tg-pill fc-tg-pill--week-group">';

                    // Linea 1: rango horario compacto
                    html += '<div class="fc-tg-time">'
                          + iconClock
                          + '<span>' + escapeHtml(rangoHorario) + '</span>'
                          + '</div>';

                    // Linea 2: "+N eventos" (texto corto)
                    html += '<div class="fc-tg-title">+' + count + ' eventos</div>';

                    // Linea 3: badge de importantes (si hay)
                    if (importantCount > 0) {
                        html += '<div class="fc-tg-important-badge">'
                              + iconAlert
                              + '<span>' + importantCount + ' importante' + (importantCount > 1 ? 's' : '') + '</span>'
                              + '</div>';
                    }

                    html += '</div>';
                    return { html: html };
                }

                // ── Pill para vista diaria (3-4 lineas) ──
                var html = '<div class="fc-tg-pill">';

                // Linea 1: hora con icono reloj
                html += '<div class="fc-tg-time">'
                      + iconClock
                      + '<span>' + escapeHtml(rangoHorario) + '</span>'
                      + '</div>';

                // Linea 2: titulo "+N eventos agendados"
                html += '<div class="fc-tg-title">+' + count + ' eventos agendados</div>';

                // Linea 3: subtexto explicativo
                html += '<div class="fc-tg-org">Eventos superpuestos en este horario</div>';

                // Linea 4: badge de importantes (si hay)
                if (importantCount > 0) {
                    html += '<div class="fc-tg-important-badge">'
                          + iconAlert
                          + '<span>' + importantCount + ' evento' + (importantCount > 1 ? 's' : '') + ' importante' + (importantCount > 1 ? 's' : '') + '</span>'
                          + '</div>';
                }

                html += '</div>';
                return { html: html };
            }

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

        // Eventos desde API — lee filtros del componente Alpine.
        // Usa cache de datos crudos para re-agrupar al cambiar de vista
        // sin repetir la petición HTTP (elimina race condition).
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
                        value.forEach(function (v) {
                            params.append(key, v);
                        });
                    } else {
                        params.set(key, value);
                    }
                });
            }

            var cacheKey = params.toString();

            // Cache hit: re-agrupar datos existentes sin llamar a la API.
            // calendar.view.type se lee solo aquí (no antes) porque en la
            // primera llamada durante render() el view puede no existir aún.
            if (rawEventsCache !== null && rawEventsCacheKey === cacheKey) {
                var viewType = calendar.view.type;
                var cached = rawEventsCache.slice();
                if (viewType === 'timeGridDay' || viewType === 'timeGridWeek') {
                    cached = groupOverlappingEvents(cached, viewType);
                }
                successCallback(cached);
                return;
            }

            // Cache miss: fetch desde la API
            fetch('/api/events?' + params.toString())
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    // Cachear datos crudos (sin agrupar)
                    rawEventsCache = data;
                    rawEventsCacheKey = cacheKey;

                    // Agrupar según vista actual
                    var currentViewType = calendar.view.type;
                    if (currentViewType === 'timeGridDay' || currentViewType === 'timeGridWeek') {
                        data = groupOverlappingEvents(data, currentViewType);
                    }
                    successCallback(data);
                })
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

            // Fecha fin del periodo visible (info.end es exclusivo, restar 1 dia)
            var endDate = new Date(info.end.getTime() - 86400000);
            var endYyyy = endDate.getFullYear();
            var endMm = String(endDate.getMonth() + 1).padStart(2, '0');
            var endDd = String(endDate.getDate()).padStart(2, '0');

            window.dispatchEvent(new CustomEvent('calendar-date-change', {
                detail: {
                    mesCorto: fmtMesCorto.format(ref).replace('.', '').toUpperCase(),
                    diaNum: ref.getDate(),
                    titulo: fmtMesLargo.format(ref),
                    diaSemana: fmtDiaSemana.format(ref),
                    viewType: info.view.type,
                    fechaISO: yyyy + '-' + mm + '-' + dd,
                    fechaFinISO: endYyyy + '-' + endMm + '-' + endDd,
                }
            }));

            // Cerrar popover semanal al cambiar de vista o navegar
            closeWeekPopover();

            // ── Refetch al cambiar a/desde vistas con agrupamiento ────
            // La funcion events() agrupa solapamientos en timeGridDay y
            // timeGridWeek. FullCalendar cachea los eventos y no re-ejecuta
            // el callback al cambiar de vista, asi que forzamos un refetch
            // cuando se entra/sale de vistas agrupadas o se cambia entre ellas
            // (el pill de grupo difiere entre diaria y semanal).
            var currentViewType = info.view.type;

            if (isRefetching) {
                // Este datesSet fue disparado por refetchEvents() — no repetir
                isRefetching = false;
                previousViewType = currentViewType;
                return;
            }

            // Vistas que requieren agrupamiento de eventos superpuestos
            var groupedViews = ['timeGridDay', 'timeGridWeek'];
            var currentNeedsGrouping = groupedViews.indexOf(currentViewType) !== -1;
            var previousNeedsGrouping = groupedViews.indexOf(previousViewType) !== -1;

            var needsRefetch = (currentNeedsGrouping && !previousNeedsGrouping)
                            || (!currentNeedsGrouping && previousNeedsGrouping)
                            || (currentNeedsGrouping && previousNeedsGrouping && currentViewType !== previousViewType);

            previousViewType = currentViewType;

            if (needsRefetch) {
                isRefetching = true;
                calendar.refetchEvents();
            }
        },

        // ── Renderizado de pills con color dinámico ────────────
        // Color por defecto: #7FBCD2 — será reemplazado por el color
        // elegido por el usuario al registrar el evento
        eventDidMount: function (info) {
            var el = info.el;
            var props = info.event.extendedProps;

            // Pill de grupo y pills normales usan el mismo esquema de color.
            // Si el evento no trae backgroundColor desde la API, se usa #7FBCD2.
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

            // Dot pulsante para eventos importantes
            if (pillColor.toUpperCase() === IMPORTANT_COLOR.toUpperCase() && !props.isGroup) {
                var viewType = info.view.type;
                var dot = document.createElement('span');
                dot.className = 'fc-important-dot';

                if (viewType === 'dayGridMonth') {
                    // En vista mensual: posicionar en esquina superior derecha
                    el.style.position = 'relative';
                    el.appendChild(dot);
                } else if (viewType === 'timeGridWeek' || viewType === 'timeGridDay') {
                    // En timeGrid: insertar dot al inicio del pill custom
                    var pillEl = el.querySelector('.fc-tg-pill');
                    if (pillEl) {
                        // Insertar antes de la primera linea del pill
                        pillEl.insertBefore(dot, pillEl.firstChild);
                    }
                }
            }
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

            // Indicador de evento importante
            if (isImportant(ev)) {
                html += '<div class="fc-tooltip-important">' + iconAlert + '<span>Importante</span></div>';
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
                if (!window.__calendarPublicMode) {
                    var fecha = info.dateStr.substring(0, 10); // YYYY-MM-DD
                    var hora = info.date.toTimeString().substring(0, 5); // HH:mm
                    var vista = calendar.view.type;
                    window.location.href = '/eventos/create?fecha=' + fecha + '&hora_inicio=' + hora + '&from=dashboard&vista=' + vista;
                }
            }
        },

        // Click en evento → panel lateral (timeGridDay desktop) o navegar a show
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            tooltip.classList.remove('fc-tooltip-visible');

            var ev = info.event;
            var props = ev.extendedProps;
            var isTimeGridWeek = info.view.type === 'timeGridWeek';
            var isTimeGridDay = info.view.type === 'timeGridDay';
            var isDesktop = window.innerWidth >= 1024;

            // ── Vista semanal: popover de detalle (toggle) ──
            if (isTimeGridWeek) {
                // Si el popover ya esta abierto para este mismo evento, cerrarlo
                if (weekPopover.style.display !== 'none' && weekPopoverEventId === ev.id) {
                    closeWeekPopover();
                    return;
                }

                // Pills de grupo → popover con acordeon
                if (props.isGroup) {
                    showWeekGroupPopover(ev, info.el);
                } else {
                    showWeekPopover(ev, info.el);
                }
                return;
            }

            // ── Click en pill de grupo — abrir panel en modo lista ──
            if (props.isGroup && isTimeGridDay && isDesktop) {
                var groupedEvents = props.groupedEvents || [];
                // Formatear cada evento del grupo para el panel
                var eventosFormateados = groupedEvents.map(function (raw) {
                    var rawProps = raw.extendedProps || {};
                    var start = raw.start ? new Date(raw.start) : null;
                    var end = raw.end ? new Date(raw.end) : null;
                    var horaStart = start ? fmtHora.format(start) : '';
                    var horaEnd = end ? fmtHora.format(end) : '';
                    var horario = horaEnd ? horaStart + ' \u2013 ' + horaEnd : horaStart;
                    var fechaTexto = start ? fmtFecha.format(start) : '';

                    return {
                        evento_id: rawProps.evento_id,
                        title: raw.title || '',
                        tipo: rawProps.tipo || '',
                        organizador: rawProps.organizador || '',
                        administracion: rawProps.administracion || '',
                        institucion: rawProps.institucion || '',
                        ubicacion: rawProps.ubicacion || '',
                        notas_cta: rawProps.notas_cta || '',
                        fechaTexto: fechaTexto,
                        horario: horario,
                        backgroundColor: raw.backgroundColor || '#7FBCD2',
                    };
                });

                window.dispatchEvent(new CustomEvent('show-event-list', {
                    detail: {
                        groupCount: props.groupCount,
                        eventos: eventosFormateados,
                    }
                }));
                return;
            }

            var eventoId = props.evento_id;

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
                        administracion: props.administracion || '',
                        ubicacion: props.ubicacion || '',
                        notas_cta: props.notas_cta || '',
                        fechaTexto: fechaTexto,
                        horarioTexto: horarioTexto,
                    }
                }));
            } else if (!window.__calendarPublicMode) {
                window.location.href = '/eventos/' + eventoId;
            }
        },
    });

    // Inicializar previousViewType con la vista inicial para que el primer
    // datesSet no dispare un refetch innecesario
    previousViewType = initialViewFromUrl;

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
        if (window.__calendarPublicMode) return;

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
        rawEventsCache = null; // Invalidar cache para forzar nuevo fetch
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
