<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MultichainController;
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

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [LoginController::class, 'index']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'create']);

    Route::prefix('multichain')->group(function () {
        Route::get('/get-information', [MultichainController::class, 'getInfo'])->name('get-information');

        Route::get('assets/create-asset', [AssetController::class, 'createAssetForm'])->name('create-asset');
        Route::post('assets/create-asset', [AssetController::class, 'createAsset'])->name('create-asset');
    });
});
