<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\SignalController;

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

/*
 *   Authentication Routes
 */

Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/login', 'login')
        ->name('login');
    Route::match(['get', 'post'], '/register/{refcode?}', 'register');
    Route::match(['post', 'get'], '/forgot-password','forgotPassword')->name('forgot-password');
    Route::post('/reset-password','doResetPassword')->name('do-reset-password');
    Route::get('/reset-password/{token}', 'resetPassword')->name('reset-password');
    Route::get('/logout', 'logout');
    Route::get('authorized/google', 'redirectToGoogle')
        ->name('auth.google');
    Route::get('authorized/google/callback', 'handleGoogleCallback');
});

/*
 *   Site Routes
 */
Route::get('/', [SiteController::class, 'index']);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/get-account-balance', [SiteController::class, 'getAccountBalance']);
    Route::get('/dashboard', [SiteController::class, 'dashboard']);
    Route::get('/market', [SiteController::class, 'market']);
    Route::match(['get', 'post'], '/deposit', [DepositController::class, 'index']);
    Route::match(['get', 'post'], '/withdrawal', [WithdrawalController::class, 'index']);
    Route::get('/account', [SiteController::class, 'account']);
    Route::get('/withdrawal-account', [SiteController::class, 'withdrawalAccount']);
    Route::get('/referral', [SiteController::class, 'referral']);
    Route::get('/transactions', [SiteController::class, 'transactions']);
    Route::get('/trade-history', [TradeController::class, 'tradeHistory']);
    Route::post('/profile', [UserController::class, 'profile']);
    Route::post('/withdrawal-account', [WithdrawalController::class, 'storeWithdrawalAccount']);
    Route::get('/get-withdrawal-accounts', [WithdrawalController::class, 'getWithdrawalAccount']);
    Route::get('payment-method/{id}', [PaymentMethodController::class, 'getPaymentMethodDetail']);
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::post('/trade', [TradeController::class, 'store']);
    Route::get('/trades/list/{filter?}', [TradeController::class, 'getTrades']);
});

/*
 *   Admin Panel Routes
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');

    Route::match(['get', 'post'], 'profile', [UserController::class, 'profile']);

    Route::post('change-password', [UserController::class, 'changePassword']);

    Route::prefix('users')
        ->group(function () {
            Route::get('/', [UserController::class, 'getUsers']);
        });

    Route::group(['prefix' => 'deposits', 'middleware' => ['can:PageAccess.Deposits']], function () {
        Route::get('/', [DepositController::class, 'getDeposits']);
        Route::post('/status', [DepositController::class, 'changeDepositStatus']);
    });

    Route::group(['prefix' => 'withdrawals', 'middleware' => ['can:PageAccess.Withdrawals']], function () {
        Route::get('/', [WithdrawalController::class, 'getWithdrawals']);
        Route::post('/status', [WithdrawalController::class, 'changeWithdrawalStatus']);
    });

    Route::prefix('payment-methods')
        ->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index']);
            Route::get('/modal', [PaymentMethodController::class, 'modal']);
            Route::get('/modal/{id}', [PaymentMethodController::class, 'modal']);
            Route::post('/store', [PaymentMethodController::class, 'store']);
            Route::post('/status', [PaymentMethodController::class, 'changePaymentStatusStatus']);
        });

    Route::group(['prefix' => 'roles', 'middleware' => ['can:PageAccess.Roles']], function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/modal', [RoleController::class, 'modal']);
        Route::get('/modal/{id}', [RoleController::class, 'modal']);
        Route::post('/store', [RoleController::class, 'store']);
        Route::post('/status', [RoleController::class, 'changePaymentStatusStatus']);
        Route::get('/permissions/{id}', [RoleController::class, 'permissionModal']);
        Route::post('/change-permission', [RoleController::class, 'changePermission']);
    });

    Route::group(['prefix' => 'system-users', 'middleware' => ['can:PageAccess.SystemUsers']], function () {
        Route::get('/', [UserController::class, 'systemUserListing']);
        Route::get('/modal', [UserController::class, 'systemUserModal']);
        Route::get('/modal/{id}', [UserController::class, 'systemUserModal']);
        Route::post('/store', [UserController::class, 'storeSystemUser']);
        Route::post('/store/{id}', [UserController::class, 'storeSystemUser']);
    });

    Route::group(['prefix' => 'settings', 'middleware' => ['can:PageAccess.Settings']], function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::post('/', [SettingController::class, 'store']);
    });

    Route::group(['prefix' => 'trades', 'middleware' => ['can:PageAccess.Trade']], function () {
        Route::get('/', [TradeController::class, 'index']);
        Route::post('/result', [TradeController::class, 'result']);
    });

    Route::group(['prefix' => 'signals', 'middleware' => ['can:PageAccess.Signal']], function () {
        Route::get('/', [SignalController::class, 'index']);
        Route::get('/modal', [SignalController::class, 'modal']);
        Route::post('/store', [SignalController::class, 'store']);
        Route::get('/{id}', [SignalController::class, 'tradesListing']);
    });


    Route::group(['prefix' => 'trading', 'middleware' => ['can:PageAccess.Trading']], function () {
        Route::get('/', [TradeController::class, 'liveTrading']);
        Route::post('/store', [TradeController::class, 'liveTradingResult']);
    });
});

# reset
Route::get('reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});
