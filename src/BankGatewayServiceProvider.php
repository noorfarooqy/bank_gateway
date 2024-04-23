<?php

namespace Noorfarooqy\BankGateway;

use Illuminate\Support\ServiceProvider;

class BankGatewayServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->publishes([
            __DIR__ . '/../config/bankgateway.php' => config_path('bankgateway.php'),
        ], 'bg-config');
    }
    public function register()
    {
    }
}
