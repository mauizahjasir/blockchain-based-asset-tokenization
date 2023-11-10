<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MultichainController;
use App\Http\Controllers\UserController;
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

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'create']);

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [LoginController::class, 'index']);

    Route::get('/new-users', [UserController::class, 'index'])->name('new-users');
    Route::post('/approve/{user}', [UserController::class, 'approve'])->name('approve');

    Route::prefix('multichain')->group(function () {
        Route::get('/get-information', [MultichainController::class, 'getInfo'])->name('get-information');

        Route::get('assets', [AssetController::class, 'index'])->name('assets');
        Route::get('assets/create-asset', [AssetController::class, 'createAssetForm'])->name('create-asset');
        Route::post('assets/create-asset', [AssetController::class, 'store'])->name('create-asset');

        Route::get('assets/create-asset-type', [AssetTypeController::class, 'index'])->name('create-asset-type');
        Route::post('assets/create-asset-type', [AssetTypeController::class, 'store'])->name('create-asset-type');
    });
});
