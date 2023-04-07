<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [AdminController::class, 'index']);

Route::post('/auth', [AdminController::class, 'auth']);

Route::get('/main', [MainController::class, 'index'])->name('main');

Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

Route::get('/services', [ServicesController::class, 'services'])->name('services');

Route::get('/separated_service', [ServicesController::class, 'separated_service'])->name('separated_service');

Route::post('/AddSeparatedService', [ServicesController::class, 'AddSeparatedService'])->name('AddSeparatedService');

Route::get('/customer_service', [ServicesController::class, 'customer_service'])->name('customer_service');

Route::post('/AddCustomerService', [ServicesController::class, 'AddCustomerService'])->name('AddCustomerService');

Route::post('/finish_service', [ServicesController::class, 'finish_service'])->name('finish_service');

Route::post('/generate_receipt', [ReceiptController::class, 'generate_receipt'])->name('generate_receipt');

Route::post('/get_customer', [ServicesController::class, 'get_customer'])->name('get_customer');

Route::post('/get_vehicle', [ServicesController::class, 'get_vehicle'])->name('get_vehicle');

Route::get('/service_receipt/{id}', [ReceiptController::class, 'index'])->name('service_receipt');
