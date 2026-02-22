import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const filtroEl = document.getElementById('filtro-institucion');

    // Tooltip reutilizable — se crea una sola vez
    const tooltip = document.createElement('div');
    tooltip.id = 'fc-tooltip';
    tooltip.style.cssText = 'position:fixed;z-index:9999;background:white;border:1px solid #e5e7eb;border-radius:8px;padding:10px 14px;font-size:13px;color:#111827;box-shadow:0 4px 12px rgba(0,0,0,0.12);pointer-events:none;max-width:260px;display:none;';
    document.body.appendChild(tooltip);

    // Formateadores de fecha y hora en español
    const fmtFecha = new Intl.DateTimeFormat('es-MX', { weekday: 'short', day: 'numeric', month: 'short' });
    const fmtHora  = new Intl.DateTimeFormat('es-MX', { hour: '2-digit', minute: '2-digit', hour12: false });

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],

        // Vistas
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,dayGridDay,listWeek',
        },

        // Localización
        locale: 'es',
        firstDay: 1,
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Agenda',
        },
        noEventsText: 'No hay eventos para mostrar',

        // Eventos desde API
        events: {
            url: '/api/events',
            method: 'GET',
            extraParams: function () {
                const params = {};
                if (filtroEl && filtroEl.value) {
                    params.institucion_id = filtroEl.value;
                }
                return params;
            },
            failure: function () {
                // Silencioso — no bloquear UI
            },
        },

        // TimeGrid (vista semanal/diaria)
        slotMinTime: '07:00:00',
        slotMaxTime: '22:00:00',
        slotDuration: '00:30:00',
        nowIndicator: true,

        // DayGrid (vista mensual)
        dayMaxEventRows: 2,

        // Altura y distribución de filas
        height: '100%',
        expandRows: true,
        fixedWeekCount: false,
        eventDisplay: 'block',

        // Tooltip en hover
        eventMouseEnter: function (info) {
            const ev    = info.event;
            const props = ev.extendedProps;

            // Contenido del tooltip
            const fecha     = ev.start ? fmtFecha.format(ev.start) : '';
            const horaStart = ev.start ? fmtHora.format(ev.start)  : '';
            const horaEnd   = ev.end   ? fmtHora.format(ev.end)    : '';
            const rango     = horaEnd ? horaStart + '\u2013' + horaEnd : horaStart;

            tooltip.innerHTML =
                '<div style="font-weight:600;margin-bottom:4px;color:#202945;">' + escapeHtml(ev.title) + '</div>' +
                (props.tipo ? '<div style="font-size:12px;color:#6b7280;margin-bottom:6px;">' + escapeHtml(props.tipo) + '</div>' : '') +
                '<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#374151;">\uD83D\uDCC5 ' + escapeHtml(fecha) + ' \u00B7 ' + escapeHtml(rango) + '</div>' +
                (props.institucion ? '<div style="font-size:12px;color:#374151;margin-top:4px;">\uD83C\uDFDB ' + escapeHtml(props.institucion) + '</div>' : '');

            // Posicionar y mostrar
            tooltip.style.display = 'block';

            const x = info.jsEvent.clientX;
            const y = info.jsEvent.clientY;
            const tw = tooltip.offsetWidth;

            // Ajuste de borde derecho del viewport
            if (x + 12 + tw > window.innerWidth) {
                tooltip.style.left = (x - tw - 12) + 'px';
            } else {
                tooltip.style.left = (x + 12) + 'px';
            }
            tooltip.style.top = (y - 10) + 'px';
        },

        eventMouseLeave: function () {
            tooltip.style.display = 'none';
        },

        // Click en evento → navegar a eventos.show
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            tooltip.style.display = 'none';
            const eventoId = info.event.extendedProps.evento_id;
            window.location.href = '/eventos/' + eventoId;
        },
    });

    calendar.render();

    // Refetch al cambiar filtro de institución
    if (filtroEl) {
        filtroEl.addEventListener('change', () => calendar.refetchEvents());
    }

    // Escapar HTML para prevenir XSS en el tooltip
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});
