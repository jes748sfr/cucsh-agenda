<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
