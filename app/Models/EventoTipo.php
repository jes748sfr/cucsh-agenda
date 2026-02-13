<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Evento> $eventos
 */
class EventoTipo extends Model
{
    protected $table = 'eventos_tipos';

    protected $fillable = [
        'nombre',
    ];

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'eventos_tipo_id');
    }
}
