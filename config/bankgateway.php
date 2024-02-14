<?php

use Noorfarooqy\BankGateway\Banks\SalaamKenya;

return [

    'configured_gateway' => 'sbk',
    'bank_gateways' => [
        'sbk' => SalaamKenya::class
    ],

];
