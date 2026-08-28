<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\ShippingServiceInterface::class,
            \App\Services\Shipping\RajaOngkirShippingService::class
        );

        $this->app->bind(
            \App\Contracts\PaymentGatewayInterface::class,
            \App\Services\Payment\MidtransPaymentGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');
    }
}
