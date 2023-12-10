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
use App\Http\Controllers\API\ClinicBalanceController;
use App\Http\Controllers\API\PatientBalanceController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\BillingDetailsController;
use App\Http\Controllers\API\GeneralWarehouseController;
use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\AppointmentAvailableSlotController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\NurseController;
use App\Http\Controllers\API\PatientFileController;
use App\Http\Controllers\API\KardexController;

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
    /*Route::get('insert_data','insert_data');*/
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
    Route::get('/list/stock', 'list');
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

Route::prefix('clinicbalance')->controller(ClinicBalanceController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/summary', 'summary');   
    Route::get('/income_expenses_statement', 'income_expenses_statement');   
    Route::get('/all_transcations', 'all_transcations');   
});

Route::prefix('patientbalance')->controller(PatientBalanceController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/balance/{id}', 'balance');   
    Route::get('/facturacion/{id}', 'facturacion');   
    Route::get('/movements/{id}', 'movements');   
    Route::post('/document/{id}', 'document');   
    Route::get('/documentlist/{id}', 'documentlist'); 
    Route::get('/document/remove/{id}', 'document_remove');
    Route::get('/document/download/{id}', 'document_download');   
});

Route::prefix('material')->controller(MaterialController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');  
    Route::post('/', 'store'); 
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy'); 
    Route::put('/stock/{id}','stock'); 
    Route::post('importmaterial', 'importmaterial'); 
    Route::post('importgeneral', 'importgeneral'); 
});

Route::prefix('billing_details')->controller(BillingDetailsController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('general_warehouse')->controller(GeneralWarehouseController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');  
    Route::post('/', 'store'); 
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy'); 
    Route::put('/stock/{id}','stock'); 
});

Route::prefix('campaign')->controller(CampaignController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');  
    Route::post('/', 'store'); 
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
    Route::get('/statistics/list','statistics');
    Route::get('/specialty/list','specialty');
    Route::get('/specialist/list/{id}','specialist');
    Route::get('/services/list/{id}','services');
    Route::post('/patients/list','send');
});

Route::prefix('room')->controller(RoomController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('room_slots')->controller(AppointmentAvailableSlotController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
    Route::post('/delete/time','deletetime');
});

Route::prefix('appointment')->controller(AppointmentController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
    Route::get('/doctor/services/{id}','doctor_services');
});

Route::prefix('nurse')->controller(NurseController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});

Route::prefix('patientfile')->controller(PatientFileController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/doctor/patient/list/{id?}', 'doctor_patient_list');
    Route::get('/doctor/patient/{id}', 'doctor_patient');
    Route::post('/save', 'save');
    Route::get('getnursingsheet','getnursingsheet');
});

Route::prefix('kardex')->controller(KardexController::class)->middleware('auth:sanctum')->group(function () {
    Route::patch('/save/{id}', 'save');
    Route::get('/{id}', 'index');
    Route::get('getnursingsheet','getnursingsheet');
});