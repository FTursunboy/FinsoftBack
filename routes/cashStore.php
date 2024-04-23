<?php

use App\Http\Controllers\Api\CashStore\AccountablePersonRefundController;
use App\Http\Controllers\Api\CashStore\AnotherCashRegisterController;
use App\Http\Controllers\Api\CashStore\ClientPaymentController;
use App\Http\Controllers\Api\CashStore\CreditReceiveController;
use App\Http\Controllers\Api\CashStore\InvestmentController;
use App\Http\Controllers\Api\CashStore\OtherExpensesController;
use App\Http\Controllers\Api\CashStore\OtherIncomesController;
use App\Http\Controllers\Api\CashStore\ProviderRefundController;
use App\Http\Controllers\Api\CashStore\WithdrawalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//

    Route::group(['prefix' => 'cash-store'], function () {
        Route::group(['prefix' => 'client-payment'], function () {
            Route::get('', [ClientPaymentController::class, 'index']);
            Route::post('', [ClientPaymentController::class, 'store']);
        });

        Route::group(['prefix' => 'withdrawal'], function () {
            Route::get('', [WithdrawalController::class, 'index']);
            Route::post('', [WithdrawalController::class, 'store']);
        });

        Route::group(['prefix' => 'another-cash-register'], function () {
            Route::get('', [AnotherCashRegisterController::class, 'index']);
            Route::post('', [AnotherCashRegisterController::class, 'store']);
        });

        Route::group(['prefix' => 'investment'], function () {
            Route::get('', [InvestmentController::class, 'index']);
            Route::post('', [InvestmentController::class, 'store']);
        });

        Route::group(['prefix' => 'credit-receive'], function () {
            Route::get('', [CreditReceiveController::class, 'index']);
            Route::post('', [CreditReceiveController::class, 'store']);
        });

        Route::group(['prefix' => 'provider-refund'], function () {
            Route::get('', [ProviderRefundController::class, 'index']);
            Route::post('', [ProviderRefundController::class, 'store']);
        });

        Route::group(['prefix' => 'accountable-person-refund'], function () {
            Route::get('', [AccountablePersonRefundController::class, 'index']);
            Route::post('', [AccountablePersonRefundController::class, 'store']);
        });

        Route::group(['prefix' => 'other-expenses'], function () {
            Route::get('', [OtherExpensesController::class, 'index']);
            Route::post('', [OtherExpensesController::class, 'store']);
            Route::get('balance-article', [OtherExpensesController::class, 'balanceArticle']);
        });

        Route::group(['prefix' => 'other-incomes'], function () {
            Route::get('', [OtherIncomesController::class, 'index']);
            Route::post('', [OtherIncomesController::class, 'store']);
        });
    });
