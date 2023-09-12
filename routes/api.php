<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClinicController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\PaymentPurposeController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\RevenueController;
use App\Http\Controllers\API\ExpensescategoryController;
use App\Http\Controllers\API\ExpensesController;
use App\Http\Controllers\API\ProviderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $response = [
        'success'=>true,
        'data'=>$request->user(),
        'message'=>'User info'
    ];

    return $response;
});

Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('register','register');
	Route::get('check','check')->name('check');
});

Route::prefix('clinic')->controller(ClinicController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('add', 'add');
    Route::get('list', 'list');
    Route::get('/index/{id}', 'index');
    Route::post('update/{id}', 'update');
    Route::post('status', 'status_update');
    Route::get('doctors/list', 'doctor_list');
    Route::get('doctor/{id}', 'doctor');
    Route::get('patients/list', 'patient_list');
    Route::get('patient/{id}', 'patient');
    Route::post('upload_picture', 'upload_picture');
    Route::get('userexist', 'userexist');
});

Route::prefix('paymentmethod')->controller(PaymentMethodController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('paymentpurpose')->controller(PaymentPurposeController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('inventory')->controller(InventoryController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('revenue')->controller(RevenueController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('expensescategory')->controller(ExpensescategoryController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('expenses')->controller(ExpensesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('provider')->controller(ProviderController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});