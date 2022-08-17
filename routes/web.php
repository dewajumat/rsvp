<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

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


Route::get('/vkontrakan/landpage', [MainController::class, 'landpage']);

Route::middleware(['auth'])->group(function () {
    Route::get('/vkontrakan/index', [MainController::class, 'index']);
    Route::get('/vkontrakan/create', [MainController::class, 'create']);
    Route::get('/vkontrakan/{no_pintu}/edit', [MainController::class, 'edit']);
    Route::post('/vkontrakan/store', [MainController::class, 'store']);
    Route::put('/vkontrakan/{no_pintu}', [MainController::class, 'update']);
    Route::delete('/{no_pintu}', [MainController::class, 'destroy']);
    Route::get('/vkontrakan/mindex', [MainController::class, 'mindex']);
    Route::get('/vkontrakan/report', [MainController::class, 'report']);
    Route::get('/vkontrakan/mcreate', [MainController::class, 'mcreate']);
    Route::post('/vkontrakan/mstore', [MainController::class, 'mstore']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
