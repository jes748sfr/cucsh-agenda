<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
        'nombre',
        'eventos_tipo_id',
        'organizador_id',
        'ubicacion',
        'activo',
        'notas_cta',
        'notas_servicios',
        'institucion_id',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function eventoTipo(): BelongsTo
    {
        return $this->belongsTo(EventoTipo::class, 'eventos_tipo_id');
    }

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(Organizador::class);
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function fechas(): HasMany
    {
        return $this->hasMany(EventoFecha::class);
    }
}
