<?php

use App\Http\Controllers\Api\ConsultaCreditoController;
use Illuminate\Support\Facades\Route;

Route::post('/consulta_credito', [ConsultaCreditoController::class, 'consultar']);
