<?php

namespace App\Providers;

use App\Http\Kernel;
use App\Repositories\AuthRepository;
use App\Repositories\BarcodeRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\CashStore\AccountablePersonRefundRepository;
use App\Repositories\CashStore\AnotherCashRegisterRepository;
use App\Repositories\CashStore\CashStoreRepository;
use App\Repositories\CashStore\ClientPaymentRepository;
use App\Repositories\CashStore\CreditReceiveRepository;
use App\Repositories\CashStore\InvestmentRepository;
use App\Repositories\CashStore\OtherExpensesRepository;
use App\Repositories\CashStore\OtherIncomesRepository;
use App\Repositories\CashStore\ProviderRefundRepository;
use App\Repositories\CashStore\SalaryPaymentRepository;
use App\Repositories\CashStore\WithdrawalRepository;
use App\Repositories\CheckingAccount\AccountablePersonRefundRepository as CheckingAccountAccountablePersonRefundRepository;
use App\Repositories\CheckingAccount\AnotherCashRegisterRepository as CheckingAccountAnotherCashRegisterRepository;
use App\Repositories\CheckingAccount\CheckingAccountRepository;
use App\Repositories\CheckingAccount\ClientPaymentRepository as CheckingAccountClientPaymentRepository;
use App\Repositories\CheckingAccount\CreditReceiveRepository as CheckingAccountCreditReceiveRepository;
use App\Repositories\CheckingAccount\InvestmentRepository as CheckingAccountInvestmentRepository;
use App\Repositories\CheckingAccount\OtherExpensesRepository as CheckingAccountOtherExpensesRepository;
use App\Repositories\CheckingAccount\OtherIncomesRepository as CheckingAccountOtherIncomesRepository;
use App\Repositories\CheckingAccount\ProviderRefundRepository as CheckingAccountProviderRefundRepository;
use App\Repositories\CheckingAccount\WithdrawalRepository as CheckingAccountWithdrawalRepository;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
use App\Repositories\Contracts\CashStore\AnotherCashRegisterRepositoryInterface;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Repositories\Contracts\CashStore\CreditReceiveRepositoryInterface;
use App\Repositories\Contracts\CashStore\InvestmentRepositoryInterface;
use App\Repositories\Contracts\CashStore\OtherExpensesRepositoryInterface;
use App\Repositories\Contracts\CashStore\OtherIncomesRepositoryInterface;
use App\Repositories\Contracts\CashStore\ProviderRefundRepositoryInterface;
use App\Repositories\Contracts\CashStore\SalaryPaymentRepositoryInterface;
use App\Repositories\Contracts\CashStore\WithdrawalRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\AccountablePersonRefundRepositoryInterface as CheckingAccountAccountablePersonRefundRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\AnotherCashRegisterRepositoryInterface as CheckingAccountAnotherCashRegisterRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\CashStoreRepositoryInterface as CheckingAccountCashStoreRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\CheckingAccountRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\CreditReceiveRepositoryInterface as CheckingAccountCreditReceiveRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\InvestmentRepositoryInterface as CheckingAccountInvestmentRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\OtherExpensesRepositoryInterface as CheckingAccountOtherExpensesRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\OtherIncomesRepositoryInterface as CheckingAccountOtherIncomesRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\ProviderRefundRepositoryInterface as CheckingAccountProviderRefundRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\WithdrawalRepositoryInterface as CheckingAccountWithdrawalRepositoryInterface;
use App\Repositories\Contracts\CounterpartyAgreementRepositoryInterface;
use App\Repositories\Contracts\CounterpartyRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\Document\ClientDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\Document\EquipmentDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\InventoryDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\InventoryOperationRepositoryInterface;
use App\Repositories\Contracts\Document\MovementDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\OrderClientDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\OrderProviderDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\ReturnClientDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\ReturnDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\ReturnProviderDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\ServiceRepositoryInterface;
use App\Repositories\Contracts\EmployeeMovementRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\ExchangeRateInterface;
use App\Repositories\Contracts\FiringRepositoryInterface;
use App\Repositories\Contracts\GoodGroupRepositoryInterface;
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use App\Repositories\Contracts\GoodRepositoryInterface;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Repositories\Contracts\HiringRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Repositories\Contracts\LocationRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\OrganizationBillRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\PriceSetUpRepositoryInterface;
use App\Repositories\Contracts\PriceTypeRepository;
use App\Repositories\Contracts\Report\CounterpartyReportRepositoryInterface;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use App\Repositories\Contracts\ReportDocumentRepositoryInterface;
use App\Repositories\Contracts\SalaryDocumentRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\StorageEmployeeRepositoryInterface;
use App\Repositories\Contracts\StorageRepositoryInterface;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\CounterpartyAgreementRepository;
use App\Repositories\CounterpartyRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\Document\ClientDocumentRepository;
use App\Repositories\Document\DocumentRepository;
use App\Repositories\Document\EquipmentDocumentRepository;
use App\Repositories\Document\InventoryDocumentRepository;
use App\Repositories\Document\InventoryOperationRepository;
use App\Repositories\Document\MovementDocumentRepository;
use App\Repositories\Document\OrderClientDocumentRepository;
use App\Repositories\Document\OrderProviderDocumentRepository;
use App\Repositories\Document\ReturnClientDocumentRepository;
use App\Repositories\Document\ReturnDocumentRepository;
use App\Repositories\Document\ReturnProviderDocumentRepository;
use App\Repositories\Document\SalaryDocumentRepository;
use App\Repositories\Document\ServiceRepository;
use App\Repositories\EmployeeMovementRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\ExchangeRateRepository;
use App\Repositories\FiringRepository;
use App\Repositories\GoodGroupRepository;
use App\Repositories\GoodRepository;
use App\Repositories\GroupRepository;
use App\Repositories\HiringRepository;
use App\Repositories\ImageRepository;
use App\Repositories\LocationRepository;
use App\Repositories\MassOperation;
use App\Repositories\NotificationRepository;
use App\Repositories\OrganizationBillRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;
use App\Repositories\Plans\Contracts\ExpenseItemSaleRepositoryInterface;
use App\Repositories\Plans\Contracts\GoodSaleRepositoryInterface;
use App\Repositories\Plans\Contracts\OldNewClientSaleRepositoryInterface;
use App\Repositories\Plans\Contracts\OperationTypeSaleRepositoryInterface;
use App\Repositories\Plans\Contracts\StorageSaleRepositoryInterface;
use App\Repositories\Plans\EmployeeSaleRepository;
use App\Repositories\Plans\ExpenseItemSaleRepository;
use App\Repositories\Plans\GoodSaleRepository;
use App\Repositories\Plans\OldNewClientSaleRepository;
use App\Repositories\Plans\OperationTypeSaleRepository;
use App\Repositories\Plans\StorageSaleRepository;
use App\Repositories\PositionRepository;
use App\Repositories\PriceSetUpRepository;
use App\Repositories\Report\CounterpartyReportRepository;
use App\Repositories\Report\GoodReportRepository;
use App\Repositories\Report\OrganizationReportRepository;
use App\Repositories\Report\ReconciliationReportRepository;
use App\Repositories\ReportCardRepository;
use App\Repositories\ReportDocumentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\StorageEmployeeRepository;
use App\Repositories\StorageRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->singleton(ReportCardRepositoryInterface::class, ReportCardRepository::class);
        $this->app->singleton(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->singleton(PriceTypeRepository::class, \App\Repositories\PriceTypeRepository::class);
        $this->app->singleton(OrganizationBillRepositoryInterface::class, OrganizationBillRepository::class);
        $this->app->singleton(CounterpartyRepositoryInterface::class, CounterpartyRepository::class);
        $this->app->singleton(CounterpartyAgreementRepositoryInterface::class, CounterpartyAgreementRepository::class);
        $this->app->singleton(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->singleton(CashRegisterRepositoryInterface::class, CashRegisterRepository::class);
        $this->app->singleton(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->singleton(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(StorageRepositoryInterface::class, StorageRepository::class);
        $this->app->singleton(CategoryRepositoryInterface::class, DepartmentRepository::class);
        $this->app->singleton(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->singleton(GoodRepositoryInterface::class, GoodRepository::class);
        $this->app->singleton(DocumentRepositoryInterface::class, DocumentRepository::class);
        $this->app->singleton(ExchangeRateInterface::class, ExchangeRateRepository::class);
        $this->app->singleton(MassOperationInterface::class, MassOperation::class);
        $this->app->singleton(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->singleton(StorageEmployeeRepositoryInterface::class, StorageEmployeeRepository::class);
        $this->app->singleton(GoodGroupRepositoryInterface::class, GoodGroupRepository::class);
        $this->app->singleton(BarcodeRepositoryInterface::class, BarcodeRepository::class);
        $this->app->singleton(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->singleton(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->singleton(MovementDocumentRepositoryInterface::class, MovementDocumentRepository::class);
        $this->app->singleton(InventoryDocumentRepositoryInterface::class, InventoryDocumentRepository::class);
        $this->app->singleton(HiringRepositoryInterface::class, HiringRepository::class);
        $this->app->singleton(EmployeeMovementRepositoryInterface::class, EmployeeMovementRepository::class);
        $this->app->singleton(FiringRepositoryInterface::class, FiringRepository::class);
        $this->app->singleton(CashStoreRepositoryInterface::class, CashStoreRepository::class);
        $this->app->singleton(ClientPaymentRepositoryInterface::class, ClientPaymentRepository::class);
        $this->app->singleton(WithdrawalRepositoryInterface::class, WithdrawalRepository::class);
        $this->app->singleton(AnotherCashRegisterRepositoryInterface::class, AnotherCashRegisterRepository::class);
        $this->app->singleton(InvestmentRepositoryInterface::class, InvestmentRepository::class);
        $this->app->singleton(CreditReceiveRepositoryInterface::class, CreditReceiveRepository::class);
        $this->app->singleton(ProviderRefundRepositoryInterface::class, ProviderRefundRepository::class);
        $this->app->singleton(AccountablePersonRefundRepositoryInterface::class, AccountablePersonRefundRepository::class);
        $this->app->singleton(OtherExpensesRepositoryInterface::class, OtherExpensesRepository::class);
        $this->app->singleton(OtherIncomesRepositoryInterface::class, OtherIncomesRepository::class);
        $this->app->singleton(CheckingAccountRepositoryInterface::class, CheckingAccountRepository::class);
        $this->app->singleton(CheckingAccountAccountablePersonRefundRepositoryInterface::class, CheckingAccountAccountablePersonRefundRepository::class);
        $this->app->singleton(CheckingAccountAnotherCashRegisterRepositoryInterface::class, CheckingAccountAnotherCashRegisterRepository::class);
        $this->app->singleton(CheckingAccountCreditReceiveRepositoryInterface::class, CheckingAccountCreditReceiveRepository::class);
        $this->app->singleton(CheckingAccountInvestmentRepositoryInterface::class, CheckingAccountInvestmentRepository::class);
        $this->app->singleton(CheckingAccountOtherExpensesRepositoryInterface::class, CheckingAccountOtherExpensesRepository::class);
        $this->app->singleton(CheckingAccountOtherIncomesRepositoryInterface::class, CheckingAccountOtherIncomesRepository::class);
        $this->app->singleton(CheckingAccountProviderRefundRepositoryInterface::class, CheckingAccountProviderRefundRepository::class);
        $this->app->singleton(CheckingAccountWithdrawalRepositoryInterface::class, CheckingAccountWithdrawalRepository::class);
        $this->app->singleton(CheckingAccountCashStoreRepositoryInterface::class, CheckingAccountClientPaymentRepository::class);
        $this->app->singleton(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->singleton(SalaryDocumentRepositoryInterface::class, SalaryDocumentRepository::class);
        $this->app->singleton(ReturnDocumentRepositoryInterface::class, ReturnDocumentRepository::class);
        $this->app->singleton(ReturnClientDocumentRepositoryInterface::class, ReturnClientDocumentRepository::class);
        $this->app->singleton(ReturnProviderDocumentRepositoryInterface::class, ReturnProviderDocumentRepository::class);
        $this->app->singleton(OrderProviderDocumentRepositoryInterface::class, OrderProviderDocumentRepository::class);
        $this->app->singleton(OrderClientDocumentRepositoryInterface::class, OrderClientDocumentRepository::class);
        $this->app->singleton(SalaryPaymentRepositoryInterface::class, SalaryPaymentRepository::class);
        $this->app->singleton(ClientDocumentRepositoryInterface::class, ClientDocumentRepository::class);
        $this->app->singleton(ReportDocumentRepositoryInterface::class, ReportDocumentRepository::class);
        $this->app->singleton(GoodReportRepositoryInterface::class, GoodReportRepository::class);
        $this->app->singleton(ReconciliationReportRepositoryInterface::class, ReconciliationReportRepository::class);
        $this->app->singleton(CounterpartyReportRepositoryInterface::class, CounterpartyReportRepository::class);
        $this->app->singleton(\App\Repositories\Contracts\Report\OrganizationReportRepository::class, OrganizationReportRepository::class);
        $this->app->singleton(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->singleton(InventoryOperationRepositoryInterface::class, InventoryOperationRepository::class);
        $this->app->singleton(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->singleton(EquipmentDocumentRepositoryInterface::class, EquipmentDocumentRepository::class);
        $this->app->singleton(GoodSaleRepositoryInterface::class, GoodSaleRepository::class);
        $this->app->singleton(EmployeeSaleRepositoryInterface::class, EmployeeSaleRepository::class);
        $this->app->singleton(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->singleton(PriceSetUpRepositoryInterface::class, PriceSetUpRepository::class);

        $this->app->singleton(StorageSaleRepositoryInterface::class, StorageSaleRepository::class);
        $this->app->singleton(OperationTypeSaleRepositoryInterface::class, OperationTypeSaleRepository::class);
        $this->app->singleton(OldNewClientSaleRepositoryInterface::class, OldNewClientSaleRepository::class);
        $this->app->singleton(ExpenseItemSaleRepositoryInterface::class, ExpenseItemSaleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     // Model::preventLazyLoading();


      \DB::whenQueryingForLongerThan(500, function(Connection $connection) {
          logger()->channel()
              ->error('whenQueryingForLonger: ',[$connection->query()->toRawSql()]);
      });


      $kernel = app(Kernel::class);

      $kernel->whenRequestLifecycleIsLongerThan(
          CarbonInterval::seconds(4),
          function() {
                logger()
                    ->channel('telegram')
                    ->debug('whenRequestLifecycleIsLongerThan:' . request()->url() );
             });

    }
}
