<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClinicController;
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
});