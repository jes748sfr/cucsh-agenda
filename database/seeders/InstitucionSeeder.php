<?php

namespace Database\Seeders;

use App\Models\Institucion;
use Illuminate\Database\Seeder;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        Institucion::create(['nombre' => 'Belenes']);
    }
}
