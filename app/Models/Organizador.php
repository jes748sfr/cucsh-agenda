<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
