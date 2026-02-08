<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
