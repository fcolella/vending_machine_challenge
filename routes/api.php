<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

use App\Infrastructure\Http\Controllers\VendingMachineController;

Route::get('/machine-state', [VendingMachineController::class, 'getVendingMachineState']);
Route::post('/insert-coin', [VendingMachineController::class, 'insertCoin']);
Route::post('/return-coin', [VendingMachineController::class, 'returnCoin']);
Route::post('/vend-item', [VendingMachineController::class, 'vendItem']);
Route::post('/service/restock', [VendingMachineController::class, 'serviceRestock']);
