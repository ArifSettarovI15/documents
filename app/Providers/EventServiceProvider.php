<?php

namespace App\Providers;

use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Companies\Models\CompaniesModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Contracts\Models\ContractTypesModel;
use App\Modules\Invoices\Models\InvoiceModel;
use App\Modules\Plans\Models\PlansModel;
use App\Modules\Services\Models\ServicesModel;
use App\Observers\ClientsObserver;
use App\Observers\ContractsObserver;
use App\Observers\ContractTypesObserver;
use App\Observers\InvoicesObserver;
use App\Observers\PlansObserver;
use App\Observers\ServicesObserver;
use App\Observers\SuppliersObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        ClientsModel::observe(ClientsObserver::class);
        CompaniesModel::observe(SuppliersObserver::class);
        ContractsModel::observe(ContractsObserver::class);
        InvoiceModel::observe(InvoicesObserver::class);
        PlansModel::observe(PlansObserver::class);
        ServicesModel::observe(ServicesObserver::class);
        ContractTypesModel::observe(ContractTypesObserver::class);
    }
}
