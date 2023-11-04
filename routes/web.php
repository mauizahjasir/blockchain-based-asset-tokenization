<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MultichainController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [LoginController::class, 'index']);

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'create']);

Route::get('/get-information', [MultichainController::class, 'getInfo'])->name('get-information');
Route::get('/create-asset', [MultichainController::class, 'createAssetForm'])->name('create-asset');
Route::post('/create-asset', [MultichainController::class, 'createAsset'])->name('create-asset');
