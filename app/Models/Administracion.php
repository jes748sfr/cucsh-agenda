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
 * @property-read Collection<int, Organizador> $organizadores
 */
class Administracion extends Model
{
    protected $table = 'administraciones';

    protected $fillable = [
        'nombre',
    ];

    public function organizadores(): HasMany
    {
        return $this->hasMany(Organizador::class);
    }
}
