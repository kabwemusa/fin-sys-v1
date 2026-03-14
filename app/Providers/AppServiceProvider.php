<?php

namespace App\Providers;

use App\Services\AccountGeneratorService;
use App\Services\LoanCalculatorService;
use App\Services\SmsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AccountGeneratorService::class);
        $this->app->singleton(LoanCalculatorService::class);
        $this->app->singleton(SmsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
