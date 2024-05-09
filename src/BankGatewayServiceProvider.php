<?php

namespace Noorfarooqy\BankGateway;

use Illuminate\Support\ServiceProvider;
use Noorfarooqy\BankGateway\Commands\SendMonthlyBankStatement;
use Noorfarooqy\BankGateway\Commands\UpdateCustomersListCommand;

class BankGatewayServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->publishes([
            __DIR__ . '/../config/bankgateway.php' => config_path('bankgateway.php'),
        ], 'bg-config');
        $this->commands([
            SendMonthlyBankStatement::class,
            UpdateCustomersListCommand::class,
        ]);
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bg');
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'bg-migrations');
    }
    public function register()
    {
    }
}
