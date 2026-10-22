<?php

use App\Http\Controllers\Admin\WsbangunController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API untuk sistem luar (WsbangunController), dimuat RouteServiceProvider di bawah
| /api dan URL lama /webadmin/api dengan middleware 'section:admin'.
|
*/

Route::post('/business/{method}/{value}', [WsbangunController::class, 'business']);
Route::post('/ticket/update/{params}', [WsbangunController::class, 'update_ticket']);
Route::post('/overtime/update/', [WsbangunController::class, 'update_ot']);
