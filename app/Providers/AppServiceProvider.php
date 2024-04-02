<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\BarcodeRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CounterpartyAgreementRepositoryInterface;
use App\Repositories\Contracts\CounterpartyRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\ExchangeRateInterface;
use App\Repositories\Contracts\GoodGroupRepositoryInterface;
use App\Repositories\Contracts\GoodRepositoryInterface;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\OrganizationBillRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\PriceTypeRepository;
use App\Repositories\Contracts\StorageEmployeeRepositoryInterface;
use App\Repositories\Contracts\StorageRepositoryInterface;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\CounterpartyAgreementRepository;
use App\Repositories\CounterpartyRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\ExchangeRateRepository;
use App\Repositories\GoodGroupRepository;
use App\Repositories\GoodRepository;
use App\Repositories\GroupRepository;
use App\Repositories\ImageRepository;
use App\Repositories\MassOperation;
use App\Repositories\OrganizationBillRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\PositionRepository;
use App\Repositories\StorageEmployeeRepository;
use App\Repositories\StorageRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthRepositoryInterface::class, AuthRepository::class);
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
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
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


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


    }
}
