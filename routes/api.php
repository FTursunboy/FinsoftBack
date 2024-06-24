<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarcodeController;
use App\Http\Controllers\Api\CashRegisterController;
use App\Http\Controllers\Api\CashStore\CashStoreController;
use App\Http\Controllers\Api\CashStore\ClientPaymentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CounterpartyAgreementController;
use App\Http\Controllers\Api\CounterpartyController;
use App\Http\Controllers\Api\CounterpartyCoordinatesController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\Document\ClientDocumentController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Document\EquipmentDocumentController;
use App\Http\Controllers\Api\Document\InventoryDocumentController;
use App\Http\Controllers\Api\Document\MovementDocumentController;
use App\Http\Controllers\Api\Document\OrderClientDocumentController;
use App\Http\Controllers\Api\Document\OrderProviderDocumentController;
use App\Http\Controllers\Api\Document\ProviderDocumentController;
use App\Http\Controllers\Api\Document\ReturnClientDocumentController;
use App\Http\Controllers\Api\Document\ReturnDocumentController;
use App\Http\Controllers\Api\Document\ReturnProviderDocumentController;
use App\Http\Controllers\Api\EmployeeMovementController;
use App\Http\Controllers\Api\FiringController;
use App\Http\Controllers\Api\GoodGroupController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\HiringController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\InventoryOperation\InventoryOperationController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\GoodController;
use App\Http\Controllers\Api\OrganizationBillController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\Plans\EmployeeSaleController;
use App\Http\Controllers\Api\Plans\ExpenseItemSaleController;
use App\Http\Controllers\Api\Plans\GoodSaleController;
use App\Http\Controllers\Api\Plans\InstallmentSaleController;
use App\Http\Controllers\Api\Plans\OldNewClientSaleController;
use App\Http\Controllers\Api\Plans\OperationTypeSaleController;
use App\Http\Controllers\Api\Plans\StorageSaleController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\PriceSetUpController;
use App\Http\Controllers\Api\PriceTypeController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\StorageEmployeeController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SettingsController;
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


Route::group(['middleware' => ['auth:sanctum', 'api.requests']], function () {
    Route::apiResource('currency', CurrencyController::class);
    Route::post('currency/default/{currency}', [CurrencyController::class, 'addDefaultCurrency']);
    Route::group(['prefix' => 'currencyRate'], function () {
        Route::post('/add/{currency}', [CurrencyController::class, 'addExchangeRate']);
        Route::get('/{currency}', [ExchangeRateController::class, 'index']);
        Route::patch('/{exchangeRate}', [CurrencyController::class, 'updateExchange']);
        Route::delete('/{exchangeRate}', [CurrencyController::class, 'removeExchangeRate']);
        Route::post('massDelete', [CurrencyController::class, 'massDeleteCurrencyRate']);
        Route::post('massRestore', [CurrencyController::class, 'massRestoreCurrencyRate']);
    });

    Route::get('getExchangeRateByCurrencyId/{currency}', [CurrencyController::class, 'getExchangeRateByCurrencyId']);
    Route::apiResource('organizationBill', OrganizationBillController::class);
    Route::apiResource('counterparty', CounterpartyController::class);
    Route::apiResource('priceType', PriceTypeController::class);
    Route::apiResource('cpAgreement', CounterpartyAgreementController::class);
    Route::apiResource('positiNon', PositionController::class);
    Route::apiResource('cashRegister', CashRegisterController::class);
    Route::apiResource('organization', OrganizationController::class);
    Route::apiResource('employee', EmployeeController::class)->except('update');
    Route::post('employee/{employee}', [EmployeeController::class, 'update']);
    Route::delete('employee/delete-image/{employee}', [EmployeeController::class, 'deleteImage']);
    Route::apiResource('user', UserController::class)->except('update');
    Route::post('user/{user}', [UserController::class, 'update']);
    Route::delete('user/delete-image/{user}', [UserController::class, 'deleteImage']);
    Route::apiResource('storage', StorageController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('unit', UnitController::class);
    Route::apiResource('good', GoodController::class)->except('update');
    Route::post('good/{good}', [GoodController::class, 'update']);
    Route::apiResource('barcode', BarcodeController::class)->except('index', 'show');
    Route::get('barcode/{good}', [BarcodeController::class, 'index']);
    Route::apiResource('group', GroupController::class)->except('index', 'show');
    Route::apiResource('good-group', GoodGroupController::class);
    Route::apiResource('schedule', ScheduleController::class);
    Route::post('calculateHours', [ScheduleController::class, 'calculateHours']);
    Route::apiResource('location', LocationController::class);

    Route::get('months', [ScheduleController::class, 'months']);

    Route::get('getExchangeRateByCurrencyId/{currency}', [CurrencyController::class, 'getExchangeRateByCurrencyId']);

    Route::get('group/show/{group}', [GroupController::class, 'show']);

    Route::group(['prefix' => 'organizationBill'], function () {
        Route::get('data/export', [OrganizationBillController::class, 'export']);
        Route::post('/massDelete', [OrganizationBillController::class, 'massDelete']);
        Route::post('/massRestore', [OrganizationBillController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/excel/export', [ScheduleController::class, 'excel']);
        Route::post('/massDelete', [ScheduleController::class, 'massDelete']);
        Route::post('/massRestore', [ScheduleController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'priceType'], function () {
        Route::get('data/export', [PriceTypeController::class, 'export']);
        Route::post('/massDelete', [PriceTypeController::class, 'massDelete']);
        Route::post('/massRestore', [PriceTypeController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'cashRegister'], function () {
        Route::get('excel/export', [CashRegisterController::class, 'export']);
        Route::post('/massDelete', [CashRegisterController::class, 'massDelete']);
        Route::post('/massRestore', [CashRegisterController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'organization'], function () {
        Route::get('excel/export', [OrganizationController::class, 'export']);
        Route::post('/massDelete', [OrganizationController::class, 'massDelete']);
        Route::post('/massRestore', [OrganizationController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'employees'], function () {
        Route::get('excel/export', [EmployeeController::class, 'export']);
        Route::post('/massDelete', [EmployeeController::class, 'massDelete']);
        Route::post('/massRestore', [EmployeeController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::post('/add-fcm-token', [UserController::class, 'addFcmToken']);
        Route::post('/change-password/{user}', [UserController::class, 'changePassword']);
        Route::post('/massDelete', [UserController::class, 'massDelete']);
        Route::post('/massRestore', [UserController::class, 'massRestore']);
        Route::get('/export', [UserController::class, 'export']);
    });

    Route::group(['prefix' => 'storage'], function () {
        Route::post('/massDelete', [StorageController::class, 'massDelete']);
        Route::post('/massRestore', [StorageController::class, 'massRestore']);
        Route::post('/access', [SettingsController::class]);
        Route::post('/massDeleteEmployee', [StorageController::class, 'massDeleteEmployee']);
        Route::post('/massRestoreEmployee', [StorageController::class, 'massRestoreEmployee']);
        Route::post('/add-employee/{storage}', [StorageController::class, 'addEmployee']);
        Route::get('/get-employees-by-storage_id/{storage}', [StorageEmployeeController::class, 'getEmployeesByStorageId']);
        Route::get('/show-employee/{employee}', [StorageController::class, 'showEmployee']);
        Route::patch('/update-employee/{employee}', [StorageController::class, 'updateEmployee']);
        Route::get('excel/export', [StorageController::class, 'export']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::post('/massDelete', [CategoryController::class, 'massDelete']);
        Route::post('/massRestore', [CategoryController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'units'], function () {
        Route::get('/export', [UnitController::class, 'export']);
        Route::get('/export', [UnitController::class, 'export']);
        Route::post('/massDelete', [UnitController::class, 'massDelete']);
        Route::post('/massRestore', [UnitController::class, 'massRestore']);
    });

    Route::apiResource('hiring', HiringController::class);

    Route::group(['prefix' => 'hiring'], function () {
       Route::post('/massDelete', [HiringController::class, 'massDelete']);
       Route::post('/massRestore', [HiringController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'goods'], function () {
        Route::get('excel/export', [GoodController::class, 'export']);
        Route::get('history/{good}', [GoodController::class, 'history']);
        Route::get('/getByBarcode/{barcode}', [GoodController::class, 'getByBarcode']);
        Route::get('count-goods', [GoodController::class, 'countGoods']);
        Route::get('count-goods-by-good-id', [GoodController::class, 'countGoodsByGoodId']);
        Route::post('/massDelete', [GoodController::class, 'massDelete']);
        Route::post('/massRestore', [GoodController::class, 'massRestore']);
    });


    Route::group(['prefix' => 'cpAgreement'], function () {
        Route::get('/getAgreementByCounterpartyId/{counterparty}', [CounterpartyAgreementController::class, 'getAgreementByCounterpartyId']);
        Route::post('/massDelete', [CounterpartyAgreementController::class, 'massDelete']);
        Route::post('/massRestore', [CounterpartyAgreementController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'counterparty'], function () {
        Route::get('data/export', [CounterpartyController::class, 'export']);
        Route::get('coordinates/{counterparty}', [CounterpartyController::class, 'getCoordinates']);
        Route::get('/clients/s', [CounterpartyController::class, 'clients']);
        Route::get('/providers/p', [CounterpartyController::class, 'providers']);
        Route::get('/restore/{counterparty}', [CounterpartyController::class, 'restore']);
        Route::post('/massDelete', [CounterpartyController::class, 'massDelete']);
        Route::post('/massRestore', [CounterpartyController::class, 'massRestore']);
    });

    Route::apiResource('department', DepartmentController::class)->except('destroy');
    Route::apiResource('employeeMovement', EmployeeMovementController::class);
    Route::apiResource('firing', FiringController::class);


    Route::group(['prefix' => 'department'], function () {
        Route::post('massDelete', [DepartmentController::class, 'destroy']);
        Route::post('restore', [DepartmentController::class, 'restore']);
    });

    Route::group(['prefix' => 'employeeMovement'], function () {
        Route::post('massDelete', [EmployeeMovementController::class, 'massDelete']);
        Route::post('massRestore', [EmployeeMovementController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'firing'], function () {
        Route::post('massDelete', [FiringController::class, 'massDelete']);
        Route::post('massRestore', [FiringController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'currency'], function () {
        Route::get('export/excel', [CurrencyController::class, 'export']);
        Route::get('/restore/{currency}', [CurrencyController::class, 'restore']);
        Route::get('/get-price-type/{currency}', [CurrencyController::class, 'getPriceType']);
        Route::post('/massDelete', [CurrencyController::class, 'massDelete']);
        Route::post('/massRestore', [CurrencyController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'position'], function () {
        Route::get('export/excel', [PositionController::class, 'export']);
        Route::post('/massDelete', [PositionController::class, 'massDelete']);
        Route::post('/massRestore', [PositionController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'good-group'], function () {
        Route::get('get-goods/{goodGroup}', [GoodGroupController::class, 'getGoods']);
        Route::get('get/goods-by-good-group', [GoodGroupController::class, 'goodsByGoodGroups']);
        Route::post('/massDelete', [GoodGroupController::class, 'massDelete']);
        Route::post('/massRestore', [GoodGroupController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'barcode'], function () {
        Route::get('get-barcode/{barcode}', [BarcodeController::class, 'show']);
        Route::post('/massDelete', [BarcodeController::class, 'massDelete']);
        Route::post('/massRestore', [BarcodeController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'group'], function () {
        Route::get('/users-group', [GroupController::class, 'usersGroup']);
        Route::get('/storages-group', [GroupController::class, 'storagesGroup']);
        Route::get('/employees-group', [GroupController::class, 'employeesGroup']);
        Route::get('/get-users/{group}', [GroupController::class, 'getUsers']);
        Route::get('/get-storages/{group}', [GroupController::class, 'getStorages']);
        Route::get('/get-employees/{group}', [GroupController::class, 'getEmployees']);
        Route::get('/restore/{group}', [GroupController::class, 'restore']);
        Route::get('/users/excel/export/{group}', [GroupController::class, 'exportUsers']);
        Route::get('/employees/excel/export/{group}', [GroupController::class, 'exportEmployees']);
        Route::get('/storages/excel/export/{group}', [GroupController::class, 'exportStorages']);
    });

    Route::group(['prefix' => 'image'], function () {
        Route::get('/{good}', [ImageController::class, 'index']);
        Route::post('/', [ImageController::class, 'store']);
        Route::delete('/{images}', [ImageController::class, 'destroy']);
    });

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'store']);

    Route::group(['prefix' => 'document'], function () {

        Route::get('/createOnBase/{document}', [DocumentController::class, 'createOnBase']);

        Route::post('copy/{document}', [DocumentController::class, 'copy']);

        Route::group(['prefix' => '/provider'], function () {
            Route::get('/purchaseList', [ProviderDocumentController::class, 'index']);
            Route::post('/purchase', [ProviderDocumentController::class, 'purchase']);

            Route::get('/returnList', [ReturnProviderDocumentController::class, 'index']);
            Route::post('/return', [ReturnProviderDocumentController::class, 'store']);
            Route::post('return/approve', [ReturnProviderDocumentController::class, 'approve']);
            Route::post('return/unApprove', [ReturnProviderDocumentController::class, 'unApprove']);
            Route::post('return/massDelete', [ReturnProviderDocumentController::class, 'massDelete']);
            Route::post('return/massRestore', [ReturnProviderDocumentController::class, 'massRestore']);

            Route::get('/orderList', [OrderProviderDocumentController::class, 'index']);
            Route::post('/order', [OrderProviderDocumentController::class, 'store']);
            Route::get('order/show/{orderDocument}', [OrderProviderDocumentController::class, 'show']);
            Route::post('order/approve', [OrderProviderDocumentController::class, 'approve']);
            Route::post('order/unApprove', [OrderProviderDocumentController::class, 'unApprove']);
            Route::post('order/massDelete', [OrderProviderDocumentController::class, 'massDelete']);
            Route::post('order/massRestore', [OrderProviderDocumentController::class, 'massRestore']);

            Route::post('order/copy/{orderDocument}', [OrderProviderDocumentController::class, 'copy']);
        });

        Route::group(['prefix' => '/client'], function () {
            Route::get('/purchasedList', [ClientDocumentController::class, 'index']);
            Route::post('/purchase', [ClientDocumentController::class, 'purchase']);
            Route::patch('update/{document}', [ClientDocumentController::class, 'update']);

            Route::get('/returnList', [ReturnClientDocumentController::class, 'index']);
            Route::post('/return', [ReturnClientDocumentController::class, 'store']);
            Route::post('return/approve', [ReturnClientDocumentController::class, 'approve']);
            Route::post('return/unApprove', [ReturnClientDocumentController::class, 'unApprove']);
            Route::post('return/massDelete', [ReturnClientDocumentController::class, 'massDelete']);
            Route::post('return/massRestore', [ReturnClientDocumentController::class, 'massRestore']);

            Route::get('orderList', [OrderClientDocumentController::class, 'index']);
            Route::post('/order', [OrderClientDocumentController::class, 'store']);
            Route::get('/order/statuses', [OrderClientDocumentController::class, 'statuses']);
            Route::get('order/show/{orderDocument}', [OrderClientDocumentController::class, 'show']);
            Route::patch('/update-order/{orderDocument}', [OrderClientDocumentController::class, 'updateOrder']);
            Route::post('order/massDelete', [OrderClientDocumentController::class, 'massDelete']);
            Route::post('order/massRestore', [OrderClientDocumentController::class, 'massRestore']);
            Route::post('order/approve', [OrderClientDocumentController::class, 'approve']);
            Route::post('order/unApprove', [OrderClientDocumentController::class, 'unApprove']);

            Route::post('delete', [ClientDocumentController::class, 'massDelete']);
            Route::post('restore', [ClientDocumentController::class, 'massRestore']);
        });

        Route::group(['prefix' => 'return'], function () {
            Route::post('approve', [ReturnDocumentController::class, 'approve']);
            Route::post('unApprove', [ReturnDocumentController::class, 'unApprove']);
        });

        Route::group(['prefix' => 'client'], function () {
            Route::post('approve', [ClientDocumentController::class, 'approve']);
            Route::post('unApprove', [ClientDocumentController::class, 'unApprove']);
        });

        Route::group(['prefix' => 'purchase'], function () {
            Route::post('approve', [ProviderDocumentController::class, 'approve']);
            Route::post('unApprove', [ProviderDocumentController::class, 'unApprove']);
        });

        Route::group(['prefix' => 'order'], function () {
            Route::post('approve', [OrderClientDocumentController::class, 'approve']);
            Route::post('unApprove', [OrderClientDocumentController::class, 'unApprove']);
        });

        Route::group(['prefix' => '/inventory'], function () {
            Route::get('/', [InventoryDocumentController::class, 'index']);
            Route::post('/', [InventoryDocumentController::class, 'store']);
            Route::get('/{inventoryDocument}', [InventoryDocumentController::class, 'show']);
            Route::patch('/{inventoryDocument}', [InventoryDocumentController::class, 'update']);
            Route::post('delete-document-goods', [InventoryDocumentController::class, 'deleteDocumentGoods']);
            Route::post('massDelete', [InventoryDocumentController::class, 'massDelete']);
            Route::post('massRestore', [InventoryDocumentController::class, 'massRestore']);
            Route::post('approve', [InventoryDocumentController::class, 'approve']);
            Route::post('unApprove', [InventoryDocumentController::class, 'unApprove']);
        });

        Route::apiResource('movement', MovementDocumentController::class)->except('destroy');
        Route::group(['prefix' => 'movement'], function () {
            Route::post('delete-document-goods', [MovementDocumentController::class, 'deleteDocumentGoods']);
            Route::post('approve', [MovementDocumentController::class, 'approve']);
            Route::post('unApprove', [MovementDocumentController::class, 'unApprove']);
            Route::post('massDelete', [MovementDocumentController::class, 'massDelete']);
            Route::post('massRestore', [MovementDocumentController::class, 'massRestore']);
        });

        Route::group(['prefix' => 'equipment'], function () {
            Route::get('', [EquipmentDocumentController::class, 'index']);
            Route::post('', [EquipmentDocumentController::class, 'store']);
            Route::get('{equipment}', [EquipmentDocumentController::class, 'show']);
            Route::patch('{equipment}', [EquipmentDocumentController::class, 'update']);
            Route::post('approve', [EquipmentDocumentController::class, 'approve']);
            Route::post('unApprove', [EquipmentDocumentController::class, 'unApprove']);
            Route::post('massDelete', [EquipmentDocumentController::class, 'massDelete']);
            Route::post('massRestore', [EquipmentDocumentController::class, 'massRestore']);
        });

        Route::patch('/update/{document}', [DocumentController::class, 'update']);
        Route::get('/show/{document}', [ProviderDocumentController::class, 'show']);
        Route::get('/document-author', [UserController::class, 'documentAuthors']);

        Route::get('/changeHistory/{document}', [DocumentController::class, 'changeHistory']);

        Route::post('delete-document-goods', [DocumentController::class, 'deleteDocumentGoods']);
        Route::post('restore', [ClientDocumentController::class, 'massRestore']);
    });

    Route::group(['middleware' => 'role:admin'], function () {
        Route::post('permission/{user}', [PermissionController::class, 'givePermission']);
        Route::get('permission/{user}', [PermissionController::class, 'getPermissions']);
        Route::get('permission/docs/{user}', [PermissionController::class, 'getDocsPermission']);
        Route::get('permission/podsystem/{user}', [PermissionController::class, 'getPodSystemPermission']);
        Route::post('permission/podsystem/{user}', [PermissionController::class, 'givePodsystemPermission']);
        Route::get('resources', [PermissionController::class, 'getResources']);
    });

    Route::group(['prefix' => 'pin'], function() {
        Route::post('/', [AuthController::class, 'addPin']);
        Route::patch('/', [AuthController::class, 'changePin']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::group(['prefix' => 'reportCard'], function () {
       Route::get('/employees', [\App\Http\Controllers\Api\ReportCardController::class, 'getEmployees']);
       Route::post('/', [\App\Http\Controllers\Api\ReportCardController::class, 'store']);
       Route::patch('/', [\App\Http\Controllers\Api\ReportCardController::class, 'update']);
       Route::get('/employeeSalary', [\App\Http\Controllers\Api\ReportCardController::class, 'getEmployeesSalary']);
       Route::get('/', [\App\Http\Controllers\Api\ReportCardController::class, 'index']);
       Route::get('/{reportCard}', [\App\Http\Controllers\Api\ReportCardController::class, 'show']);
       Route::post('/massDelete', [\App\Http\Controllers\Api\ReportCardController::class, 'massDelete']);
       Route::post('/massRestore', [\App\Http\Controllers\Api\ReportCardController::class, 'massRestore']);
    });


    Route::group(['prefix' => 'salaryDocument'], function () {
        Route::get('/', [\App\Http\Controllers\Api\SalaryDocumentController::class, 'index']);
        Route::get('/{salaryDocument}', [\App\Http\Controllers\Api\SalaryDocumentController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\SalaryDocumentController::class, 'store']);
        Route::patch('/{salaryDocument}', [\App\Http\Controllers\Api\SalaryDocumentController::class, 'update']);
        Route::post('/massDelete', [\App\Http\Controllers\Api\SalaryDocumentController::class, 'massDelete']);
        Route::post('/massRestore', [\App\Http\Controllers\Api\SalaryDocumentController::class, 'massRestore']);
    });

    Route::group(['prefix' => 'document-report'], function () {
        Route::get('/balance/{document}', [\App\Http\Controllers\Api\ReportDocumentController::class, 'getBalance']);
        Route::get('/counterparty-settlements/{document}', [\App\Http\Controllers\Api\ReportDocumentController::class, 'getCounterpartySettlements']);
        Route::get('/good-accountings/{document}', [\App\Http\Controllers\Api\ReportDocumentController::class, 'getGoodAccountings']);
    });


    Route::group(['prefix' => 'inventoryOperation'], function () {
        Route::get('/{type}', [InventoryOperationController::class, 'index']);
        Route::post('/', [InventoryOperationController::class, 'store']);
        Route::get('/show/{document}', [InventoryOperationController::class, 'show']);
        Route::patch('/{document}', [InventoryOperationController::class, 'show']);
        Route::post('/massDelete', [InventoryOperationController::class, 'massDelete']);
        Route::post('/massRestore', [InventoryOperationController::class, 'massRestore']);
        Route::post('/approve', [InventoryOperationController::class, 'approve']);
        Route::post('/unApprove', [InventoryOperationController::class, 'unApprove']);
    });

    Route::group(['prefix' => 'counterpartyCoordinates'], function () {
        Route::post('/', [CounterpartyCoordinatesController::class, 'store']);
        Route::get('/', [CounterpartyCoordinatesController::class, 'index']);
    });

    Route::group(['prefix' => 'service'], function () {
        Route::post('/', [ServiceController::class, 'store']);
    });



    Route::get('/operationTypes', [CashStoreController::class, 'getOperationTypes']);



    Route::group(['prefix' => 'plan'], function (){
        Route::post('goods', [GoodSaleController::class, 'store']);
        Route::get('goods/{plan}', [GoodSaleController::class, 'show']);
        Route::get('goods', [GoodSaleController::class, 'index']);
        Route::patch('goods/{plan}', [GoodSaleController::class, 'update']);
        Route::post('goods/massDelete', [GoodSaleController::class, 'massDelete']);
        Route::post('goods/massRestore', [GoodSaleController::class, 'massRestore']);

        Route::post('employees', [EmployeeSaleController::class, 'store']);
        Route::get('employees/{plan}', [EmployeeSaleController::class, 'show']);
        Route::get('employees', [EmployeeSaleController::class, 'index']);
        Route::patch('employees/{plan}', [EmployeeSaleController::class, 'update']);
        Route::post('employees/massDelete', [EmployeeSaleController::class, 'massDelete']);
        Route::post('employees/massRestore', [EmployeeSaleController::class, 'massRestore']);

        Route::post('storages', [StorageSaleController::class, 'store']);
        Route::get('storages/{plan}', [StorageSaleController::class, 'show']);
        Route::get('storages', [StorageSaleController::class, 'index']);
        Route::patch('storages/{plan}', [StorageSaleController::class, 'update']);
        Route::post('storages/massDelete', [StorageSaleController::class, 'massDelete']);
        Route::post('storages/massRestore', [StorageSaleController::class, 'massRestore']);

        Route::post('operation-types', [OperationTypeSaleController::class, 'store']);
        Route::get('operation-types/{plan}', [OperationTypeSaleController::class, 'show']);
        Route::get('operation-types', [OperationTypeSaleController::class, 'index']);
        Route::patch('operation-types/{plan}', [OperationTypeSaleController::class, 'update']);
        Route::post('operation-types/massDelete', [OperationTypeSaleController::class, 'massDelete']);
        Route::post('operation-types/massRestore', [OperationTypeSaleController::class, 'massRestore']);

        Route::post('old-new-client', [OldNewClientSaleController::class, 'store']);
        Route::get('old-new-client/{plan}', [OldNewClientSaleController::class, 'show']);
        Route::get('old-new-client', [OldNewClientSaleController::class, 'index']);
        Route::patch('old-new-client/{plan}', [OldNewClientSaleController::class, 'update']);
        Route::post('old-new-client/massDelete', [OldNewClientSaleController::class, 'massDelete']);
        Route::post('old-new-client/massRestore', [OldNewClientSaleController::class, 'massRestore']);

        Route::post('installment', [InstallmentSaleController::class, 'store']);
        Route::get('installment/{plan}', [InstallmentSaleController::class, 'show']);
        Route::get('installment', [InstallmentSaleController::class, 'index']);
        Route::patch('installment/{plan}', [InstallmentSaleController::class, 'update']);
        Route::post('installment/massDelete', [InstallmentSaleController::class, 'massDelete']);
        Route::post('installment/massRestore', [InstallmentSaleController::class, 'massRestore']);

        Route::post('expense-item', [ExpenseItemSaleController::class, 'store']);
        Route::get('expense-item/{plan}', [ExpenseItemSaleController::class, 'show']);
        Route::get('expense-item', [ExpenseItemSaleController::class, 'index']);
        Route::patch('expense-item/{plan}', [ExpenseItemSaleController::class, 'update']);
        Route::post('expense-item/massDelete', [ExpenseItemSaleController::class, 'massDelete']);
        Route::post('expense-item/massRestore', [ExpenseItemSaleController::class, 'massRestore']);
    });


    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/',  [NotificationController::class, 'getUnreadNotifications']);
        Route::post('read/{notification}',  [NotificationController::class, 'read']);
        Route::get('all',  [NotificationController::class, 'getAllNotifications']);
    });

    Route::group(['prefix' => 'priceSetUp'], function () {
        Route::get('/', [PriceSetUpController::class, 'index']);
    });

    require_once 'cashStore.php';
    require_once 'checkingAccount.php';


    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
});


require_once 'reports.php';



Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('checkCode', [AuthController::class, 'checkCode']);


