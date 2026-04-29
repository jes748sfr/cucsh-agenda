<?php

namespace App\Mail;

use App\Models\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notificacion al CTA cuando un evento incluye o actualiza notas_cta.
 */
class NotaGeneralesNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Crear nueva instancia del mailable.
     */
    public function __construct(
        public Evento $evento,
        public bool $esActualizacion = false,
    ) {}

    /**
     * Sobre del correo.
     */
    public function envelope(): Envelope
    {
        $accion = $this->esActualizacion ? 'Actualizado' : 'Nuevo';

        return new Envelope(
            to: [config('cucsh.generales_email')],
            subject: "Notas Servicios generales ({$accion}) - {$this->evento->nombre}",
        );
    }

    /**
     * Contenido del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nota-generales',
        );
    }
}
