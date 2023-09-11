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
    Route::get('/forgot', 'forgot');
    Route::get('/reset', 'reset');
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
    Route::get('/market', [SiteController::class, 'market']);
    Route::match(['get', 'post'], '/deposit', [DepositController::class, 'index']);
    Route::match(['get', 'post'], '/withdrawal', [WithdrawalController::class, 'index']);
    Route::get('/account', [SiteController::class, 'account']);
    Route::get('/referral', [SiteController::class, 'referral']);
    Route::get('/transactions', [SiteController::class, 'transactions']);
    Route::get('/trade-history', [SiteController::class, 'tradeListing']);
    Route::post('/profile', [UserController::class, 'profile']);
    Route::post('/withdrawal-account', [WithdrawalController::class, 'storeWithdrawalAccount']);
    Route::get('payment-method/{id}', [PaymentMethodController::class, 'getPaymentMethodDetail']);
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
});

# reset
Route::get('reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});
