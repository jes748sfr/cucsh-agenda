<?php

use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoTipoController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\OrganizadorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Catálogos - solo administrador puede gestionar
    Route::middleware('permission:catalogos.manage')->group(function () {
        Route::resource('eventos-tipos', EventoTipoController::class);
        Route::resource('instituciones', InstitucionController::class)
            ->parameters(['instituciones' => 'institucion']);
        Route::resource('administraciones', AdministracionController::class)
            ->parameters(['administraciones' => 'administracion']);
        Route::resource('organizadores', OrganizadorController::class)
            ->parameters(['organizadores' => 'organizador']);
    });

    // Eventos - permisos granulares por acción
    Route::get('eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::middleware('permission:eventos.create')->group(function () {
        Route::get('eventos/create', [EventoController::class, 'create'])->name('eventos.create');
        Route::post('eventos', [EventoController::class, 'store'])->name('eventos.store');
    });
    Route::get('eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
    Route::middleware('permission:eventos.edit')->group(function () {
        Route::get('eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
        Route::put('eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
        Route::patch('eventos/{evento}', [EventoController::class, 'update']);
    });
    Route::delete('eventos/{evento}', [EventoController::class, 'destroy'])
        ->middleware('permission:eventos.delete')
        ->name('eventos.destroy');
});

require __DIR__.'/auth.php';
