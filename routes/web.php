<?php

use App\Http\Controllers\AdminAssetController;
use App\Http\Controllers\AdminAssetsRequestController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetOnSaleController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AssetsRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomingRequestController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OutgoingRequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionHistoryController;
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

    Route::get('/home', HomeController::class)->name('home');
    Route::get('/', LoginController::class);

    Route::prefix('assets')->group(function () {
        Route::post('/put-on-sale', [AssetOnSaleController::class, 'putOnSale'])->name('put-on-sale');
        Route::post('/remove-from-sale', [AssetOnSaleController::class, 'removeFromSale'])->name('remove-from-sale');
        Route::get('/assets-on-sale', [AssetOnSaleController::class, 'assetsOnSalePage'])->name('assets-on-sale');
        Route::post('/{assetOnSale}/request-purchase', [AssetsRequestController::class, 'requestPurchase'])->name('request-purchase');
        Route::post('/bank/assets/request-purchase', [AssetsRequestController::class, 'bankAssetPurchase'])->name('bank-request-purchase');
        Route::get('/request/outgoing-requests', [OutgoingRequestController::class, 'index'])->name('outgoing-requests');
        Route::get('/request/outgoing-requests/history', [OutgoingRequestController::class, 'historicalData'])->name('outgoing-requests-history');
        Route::get('/request/incoming-requests', [IncomingRequestController::class, 'index'])->name('incoming-requests');
        Route::get('/request/incoming-requests/history', [IncomingRequestController::class, 'historicalData'])->name('incoming-requests-history');
        Route::post('/request/incoming-requests/{assetRequest}/approve', [IncomingRequestController::class, 'approve'])->name('incoming-requests-approve');
        Route::post('/request/incoming-requests/{assetRequest}/reject', [IncomingRequestController::class, 'reject'])->name('incoming-requests-reject');
        Route::post('/request/outgoing-requests/{assetRequest}/approve', [OutgoingRequestController::class, 'approve'])->name('outgoing-requests-approve');
        Route::post('/request/outgoing-requests/{assetRequest}/reject', [OutgoingRequestController::class, 'reject'])->name('outgoing-requests-reject');
    });

    Route::prefix('client')->group(function () {
        Route::get('/bank-assets', [AssetController::class, 'bankAssets'])->name('bank-assets');
        Route::get('/my-assets', [AssetController::class, 'clientAssets'])->name('client-assets');
    });

    //...................................//

    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('all-users');
        Route::get('new-users', [UserController::class, 'newUsers'])->name('new-users');
        Route::post('approve/{user}', [UserController::class, 'approve'])->name('approve');

        Route::get('users/manage-permissions', [PermissionController::class, 'index'])->name('users.manage-permissions');
        Route::post('user/{user}/grant-permission', [PermissionController::class, 'grantPermission'])->name('grant-permission');
        Route::post('user/{user}/revoke-permission', [PermissionController::class, 'revokePermission'])->name('revoke-permission');

        Route::get('assets', [AdminAssetController::class, 'index'])->name('admin.assets');

        Route::prefix('assetType')->group(function () {
            Route::get('create', [AssetTypeController::class, 'index'])->name('asset-type.create');
            Route::post('create', [AssetTypeController::class, 'store'])->name('asset-type.create');
        });

        Route::prefix('assets')->group(function () {
            Route::get('create', [AdminAssetController::class, 'createAssetView'])->name('assets.create');
            Route::post('create', [AdminAssetController::class, 'store'])->name('assets.create');

            Route::get('requests', [AdminAssetsRequestController::class, 'index'])->name('admin.requests');
            Route::post('requests/{assetRequest}/approve', [AdminAssetsRequestController::class, 'approve'])->name('admin.requests.approve');
            Route::post('requests/{assetRequest}/reject', [AdminAssetsRequestController::class, 'reject'])->name('admin.requests.reject');
            Route::get('requests/{assetRequest}/details', [AdminAssetsRequestController::class, 'details'])->name('admin.requests.details');

            Route::prefix('transactions')->group(function () {

                Route::get('/', [TransactionController::class, 'index'])->name('assets.transactions');
                Route::post('/{assetRequest}/approve', [TransactionController::class, 'approve'])->name('assets.transactions.approve');
                Route::post('/{assetRequest}/reject', [TransactionController::class, 'reject'])->name('assets.transactions.reject');
                Route::get('/{assetRequest}/details', [TransactionController::class, 'requestDetails'])->name('assets.transactions.detail');

                Route::get('history', [TransactionHistoryController::class, 'index'])->name('transactions.history');
            });
        });
    });

});
