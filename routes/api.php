<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');

Route::get('/validarToken', [AuthController::class, 'validarToken'])->middleware('auth:api')->name('auth.validarToken');
