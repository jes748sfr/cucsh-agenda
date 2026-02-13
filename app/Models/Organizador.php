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
 * @property string|null $tel
 * @property string $email
 * @property int $administracion_id
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Administracion $administracion
 * @property-read Collection<int, Evento> $eventos
 */
class Organizador extends Model
{
    protected $table = 'organizadores';

    protected $fillable = [
        'nombre',
        'tel',
        'email',
        'administracion_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function administracion(): BelongsTo
    {
        return $this->belongsTo(Administracion::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
