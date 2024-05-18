<?php

use App\Http\Controllers\Api\CashStore\AccountablePersonRefundController;
use App\Http\Controllers\Api\CashStore\AnotherCashRegisterController;
use App\Http\Controllers\Api\CashStore\CashStoreController;
use App\Http\Controllers\Api\CashStore\ClientPaymentController;
use App\Http\Controllers\Api\CashStore\CreditReceiveController;
use App\Http\Controllers\Api\CashStore\InvestmentController;
use App\Http\Controllers\Api\CashStore\OtherExpensesController;
use App\Http\Controllers\Api\CashStore\OtherIncomesController;
use App\Http\Controllers\Api\CashStore\ProviderRefundController;
use App\Http\Controllers\Api\CashStore\SalaryPaymentController;
use App\Http\Controllers\Api\CashStore\WithdrawalController;
use App\Http\Controllers\Api\GoodReportController;
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

    Route::group(['prefix' => 'report'], function () {
        Route::get('goodAccounting', [GoodReportController::class, 'index']);
        Route::get('goodExcel', [GoodReportController::class, 'export']);
    });
