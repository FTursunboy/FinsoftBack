<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\CashStore;
use App\Models\Department;
use App\Models\EmployeeMovement;
use App\Models\Firing;
use App\Models\Hiring;
use App\Models\MovementDocument;
use App\Models\ReportCard;
use App\Models\SalaryDocument;
use App\Policies\CashStorePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeeMovementPolicy;
use App\Policies\FiringPolicy;
use App\Policies\HiringPolicy;
use App\Policies\MovementDocumentPolicy;
use App\Policies\ReportCardPolicy;
use App\Policies\SalaryDocumentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        MovementDocument::class => MovementDocumentPolicy::class,
        CashStore::class => CashStorePolicy::class,
        Department::class => DepartmentPolicy::class,
        Hiring::class => HiringPolicy::class,
        EmployeeMovement::class => EmployeeMovementPolicy::class,
        Firing::class => FiringPolicy::class,
        ReportCard::class => ReportCardPolicy::class,
        SalaryDocument::class => SalaryDocumentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
