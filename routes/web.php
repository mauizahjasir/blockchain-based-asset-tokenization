<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetOnSaleController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AssetsRequestController;
use App\Http\Controllers\IncomingRequestController;
use App\Http\Controllers\MultichainController;
use App\Http\Controllers\OutgoingRequestController;
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

    Route::prefix('assets')->group(function () {
        Route::post('/put-on-sale', [AssetOnSaleController::class, 'putOnSale'])->name('put-on-sale');
        Route::post('/remove-from-sale', [AssetOnSaleController::class, 'removeFromSale'])->name('remove-from-sale');
        Route::post('/remove-from-sale', [AssetOnSaleController::class, 'removeFromSale'])->name('remove-from-sale');
        Route::get('/assets-on-sale', [AssetOnSaleController::class, 'assetsOnSalePage'])->name('assets-on-sale');
        Route::post('/{assetOnSale}/request-purchase', [AssetsRequestController::class, 'requestPurchase'])->name('request-purchase');
        Route::post('/{assetOnSale}/request-purchase', [AssetsRequestController::class, 'requestPurchase'])->name('request-purchase');
        Route::get('/request/outgoing-requests', [OutgoingRequestController::class, 'index'])->name('outgoing-requests');
        Route::get('/request/incoming-requests', [IncomingRequestController::class, 'index'])->name('incoming-requests');
        Route::post('/request/incoming-requests/{assetRequest}/approve', [IncomingRequestController::class, 'approve'])->name('incoming-requests-approve');
        Route::post('/request/outgoing-requests/{assetRequest}/approve', [OutgoingRequestController::class, 'approve'])->name('outgoing-requests-approve');
    });

    Route::prefix('client')->group(function () {
        Route::get('/bank-assets', [AssetController::class, 'bankAssets'])->name('bank-assets');
        Route::get('/my-assets', [AssetController::class, 'clientAssets'])->name('client-assets');
    });

    Route::prefix('multichain')->middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'allUsers'])->name('all-users');

        Route::get('/manage-permissions', [MultichainController::class, 'managePermissions'])->name('manage-permissions');
        Route::post('user/{user}/grant-permission', [UserController::class, 'grantPermission'])->name('grant-permission');
        Route::post('user/{user}/revoke-permission', [UserController::class, 'revokePermission'])->name('revoke-permission');

        Route::get('admin/assets', [AssetController::class, 'index'])->name('all-assets');
        Route::get('admin/my-assets', [AssetController::class, 'adminAssets'])->name('my-assets');
        Route::get('assets/create-asset', [AssetController::class, 'createAssetForm'])->name('create-asset');
        Route::post('assets/create-asset', [AssetController::class, 'store'])->name('create-asset');

        Route::get('assets/create-asset-type', [AssetTypeController::class, 'index'])->name('create-asset-type');
        Route::post('assets/create-asset-type', [AssetTypeController::class, 'store'])->name('create-asset-type');

        Route::get('assets/requests', [AssetsRequestController::class, 'index'])->name('asset-requests');
        Route::post('assets/requests/{assetRequest}/approve', [AssetsRequestController::class, 'requestApprove'])->name('request-approve');
        Route::post('assets/requests/{assetRequest}/reject', [AssetsRequestController::class, 'requestReject'])->name('request-reject');
        Route::get('assets/requests/{assetRequest}/details', [AssetsRequestController::class, 'requestDetails'])->name('request-details');
        Route::get('assets/requests/{assetRequest}/details', [AssetsRequestController::class, 'requestDetails'])->name('request-details');
    });

});
