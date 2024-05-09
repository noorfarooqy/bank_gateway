<?php

use Noorfarooqy\BankGateway\Banks\SalaamKenya;

return [

    'configured_gateway' => 'sbk',
    'bank_gateways' => [
        'sbk' => SalaamKenya::class
    ],
    //Class balances for each account class
    'class_balances' => [
        'classes' => [],
        'limits' => [],
    ],
    'reports' => [
        'statement' => [],
        'static' => [
            'from_date' => env('BG_REPORT_STATIC_FROM_DATE', ''),

        ],
    ],

];
