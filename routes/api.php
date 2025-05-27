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
Route::prefix('bills-payments-api/v1')->group(function () {
    Route::post('/inquiry', [BillsPaymentController::class, 'inquiry']);
    Route::post('/payment', [BillsPaymentController::class, 'payment']);
    Route::post('/status-check', [BillsPaymentController::class, 'statusCheck']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('1')->group(function () {
    Route::get('/2/{institution_id}/{member_id}', [BillsPaymentController::class, 'inquiryGet']);
    Route::post('/3', [BillsPaymentController::class, 'payment']);
    Route::post('/4', [BillsPaymentController::class, 'statusCheck']);
});

Route::prefix('7')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Test route working',
            'data' => '2'
        ]);
    });
});