import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const filtroEl = document.getElementById('filtro-institucion');

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
                return {
                    institucion_id: filtroEl ? filtroEl.value : '',
                };
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

        // Click en evento → drawer Alpine.js
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const props = info.event.extendedProps;
            window.dispatchEvent(new CustomEvent('show-evento', {
                detail: {
                    evento_id:   props.evento_id,
                    title:       info.event.title,
                    start:       info.event.start,
                    end:         info.event.end,
                    institucion: props.institucion,
                    tipo:        props.tipo,
                    organizador: props.organizador,
                    ubicacion:   props.ubicacion || '',
                },
            }));
        },
    });

    calendar.render();

    // Refetch al cambiar filtro de institución
    if (filtroEl) {
        filtroEl.addEventListener('change', () => calendar.refetchEvents());
    }
});
