<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\TareaController;


// Routing de maquina
Route::apiResource('maquina', MaquinaController::class);

// Routing de produccion
Route::apiResource('produccion', ProduccionController::class)->only(['index', 'show']);

// Routing de tarea
Route::apiResource('tarea', TareaController::class);