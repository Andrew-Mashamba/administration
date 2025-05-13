<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BillsPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// NBC Bills Payment API Routes
Route::prefix('bills-payments-api/api/v1')->group(function () {
    Route::post('/inquiry', [BillsPaymentController::class, 'inquiry']);
    Route::post('/payment', [BillsPaymentController::class, 'payment']);
    Route::post('/status-check', [BillsPaymentController::class, 'statusCheck']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


