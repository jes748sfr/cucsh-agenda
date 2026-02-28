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
 * @property int|null $institucion_id
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Institucion|null $institucion
 * @property-read Collection<int, Evento> $eventos
 */
class Ubicacion extends Model
{
    protected $table = 'ubicaciones';

    protected $fillable = [
        'nombre',
        'institucion_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
