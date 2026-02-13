<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $evento_id
 * @property Carbon $fecha
 * @property Carbon $hora_inicio
 * @property Carbon $hora_fin
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Evento $evento
 */
class EventoFecha extends Model
{
    protected $table = 'eventos_fechas';

    protected $fillable = [
        'evento_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'hora_inicio' => 'datetime:H:i',
            'hora_fin' => 'datetime:H:i',
        ];
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }
}
