<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\CashStore;
use App\Models\Department;
use App\Models\MovementDocument;
use App\Policies\CashStorePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\MovementDocumentPolicy;
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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
