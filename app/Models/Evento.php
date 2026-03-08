<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nombre
 * @property int $eventos_tipo_id
 * @property int $organizador_id
 * @property int|null $ubicacion_id
 * @property bool $activo
 * @property string $color
 * @property string|null $notas_cta
 * @property string|null $notas_servicios
 * @property int $institucion_id
 * @property int $usuario_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EventoTipo $eventoTipo
 * @property-read Organizador $organizador
 * @property-read Institucion $institucion
 * @property-read Ubicacion|null $ubicacion_rel
 * @property-read User $usuario
 * @property-read Collection<int, EventoFecha> $fechas
 */
class Evento extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
        'nombre',
        'eventos_tipo_id',
        'organizador_id',
        'ubicacion_id',
        'activo',
        'color',
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

    public function ubicacionRel(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
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
