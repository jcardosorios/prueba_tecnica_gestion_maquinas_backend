<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinaController;

Route::get('/maquina', function (Request $request) {
    return $request->maquina();
})->middleware('auth:sanctum');

Route::apiResource('maquina', MaquinaController::class);