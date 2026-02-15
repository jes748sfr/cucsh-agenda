<?php

use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoTipoController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\OrganizadorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Catalogos - lectura (catalogos.ver)
    Route::middleware('permission:catalogos.ver')->group(function () {
        Route::resource('eventos-tipos', EventoTipoController::class)->only(['index', 'show']);
        Route::resource('instituciones', InstitucionController::class)
            ->parameters(['instituciones' => 'institucion'])->only(['index', 'show']);
        Route::resource('administraciones', AdministracionController::class)
            ->parameters(['administraciones' => 'administracion'])->only(['index', 'show']);
    });

    // Catalogos - escritura (role:administrador)
    Route::middleware('role:administrador')->group(function () {
        Route::resource('eventos-tipos', EventoTipoController::class)->except(['index', 'show']);
        Route::resource('instituciones', InstitucionController::class)
            ->parameters(['instituciones' => 'institucion'])->except(['index', 'show']);
        Route::resource('administraciones', AdministracionController::class)
            ->parameters(['administraciones' => 'administracion'])->except(['index', 'show']);
    });

    // Organizadores - lectura (organizadores.ver)
    Route::middleware('permission:organizadores.ver')->group(function () {
        Route::resource('organizadores', OrganizadorController::class)
            ->parameters(['organizadores' => 'organizador'])->only(['index', 'show']);
    });

    // Organizadores - escritura (policies controlan acceso granular)
    Route::resource('organizadores', OrganizadorController::class)
        ->parameters(['organizadores' => 'organizador'])->except(['index', 'show']);

    // Usuarios - permisos granulares por accion
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:usuarios.ver')->name('users.index');
    Route::middleware('permission:usuarios.crear')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });
    Route::get('users/{user}', [UserController::class, 'show'])
        ->middleware('permission:usuarios.ver')->name('users.show');
    Route::middleware('permission:usuarios.editar')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:usuarios.eliminar')
        ->name('users.destroy');

    // Eventos - permisos granulares por accion
    // Orden importante: /create ANTES de /{evento} para evitar captura de "create" como parametro
    Route::get('eventos', [EventoController::class, 'index'])
        ->middleware('permission:eventos.ver')->name('eventos.index');
    Route::middleware('permission:eventos.crear')->group(function () {
        Route::get('eventos/create', [EventoController::class, 'create'])->name('eventos.create');
        Route::post('eventos', [EventoController::class, 'store'])->name('eventos.store');
    });
    Route::get('eventos/{evento}', [EventoController::class, 'show'])
        ->middleware('permission:eventos.ver')->name('eventos.show');
    Route::middleware('permission:eventos.editar')->group(function () {
        Route::get('eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
        Route::put('eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
        Route::patch('eventos/{evento}', [EventoController::class, 'update']);
    });
    Route::delete('eventos/{evento}', [EventoController::class, 'destroy'])
        ->middleware('permission:eventos.eliminar')
        ->name('eventos.destroy');
});

require __DIR__.'/auth.php';
