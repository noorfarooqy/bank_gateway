<?php

namespace Noorfarooqy\BankGateway;

use Illuminate\Support\ServiceProvider;
use Noorfarooqy\BankGateway\Commands\SendMonthlyBankStatement;

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
        ]);
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bg');
    }
    public function register()
    {
    }
}
