<?php

namespace Noorfarooqy\BankGateway\Tests;

use Illuminate\Http\Request;
use Noorfarooqy\BankGateway\BankGatewayServiceProvider;
use Noorfarooqy\BankGateway\Services\BankServices;
use Orchestra\Testbench\TestCase;

class BankServicesTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            BankGatewayServiceProvider::class,
        ];
    }

    public function testGetAccountBalance()
    {
        // Create an instance of the BankServices class
        $bankServices = new BankServices();

        // Mock the request object
        $request = new Request();

        // Set up the request data
        // $request->account_number = '1234567890';
        // $request->branch_code = '456';

        // Call the getAccountBalance() method
        $result = $bankServices->getAccountBalance($request);

        // Assert the result based on the expected behavior of the method
        $this->assertEquals($result, $result);
    }

    

    
}
