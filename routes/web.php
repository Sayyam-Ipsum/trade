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

    Route::prefix('users')
        ->group(function () {
            Route::get('/', [UserController::class, 'getUsers']);
        });

    Route::prefix('deposits')
        ->group(function () {
            Route::get('/', [DepositController::class, 'getDeposits']);
            Route::post('/status', [DepositController::class, 'changeDepositStatus']);
        });

    Route::prefix('withdrawals')
        ->group(function () {
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

    Route::prefix('settings')->group(function () {
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
