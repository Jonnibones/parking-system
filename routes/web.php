<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ServicesController;
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

Route::get('/separated_service', [ServicesController::class, 'separated_service'])->name('separated_service');

Route::post('/AddSeparatedService', [ServicesController::class, 'AddSeparatedService'])->name('AddSeparatedService');

Route::post('/finish_service', [ServicesController::class, 'finish_service'])->name('finish_service');
