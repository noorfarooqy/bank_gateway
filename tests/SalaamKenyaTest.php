<?php

namespace Noorfarooqy\BankGateway\Tests\Banks;

use Noorfarooqy\BankGateway\Banks\SalaamKenya;
use Orchestra\Testbench\TestCase;

class SalaamKenyaTest extends TestCase
{
    public function test_get_balance()
    {
        $bank = new SalaamKenya();
        $balance = $bank->getBalance('1234567890', '456');
        $this->assertEquals([
            'account' => '1234567890',
            'ccy' => 'KES',
            'branch' => '456',
            'opening_valance' => '0.00',
            'current_balance' => '12345.67',
            'available_balance' => '12345.67',
            'block_amount' => '0.00',
        ], $balance);
    }
}
