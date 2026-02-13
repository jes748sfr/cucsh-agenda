<?php

use App\Http\Controllers\Api\EventoApiController;
use Illuminate\Support\Facades\Route;

Route::get('/events', [EventoApiController::class, 'index']);
