<?php

namespace Noorfarooqy\BankGateway\Banks;

use Noorfarooqy\Flexcube\Services\FlexcubeServices;
use Noorfarooqy\NoorAuth\Traits\ResponseHandler;

class SalaamKenya extends Bank
{
    use ResponseHandler;
    public function getBalance($account, $branch = null): object
    {
        $flexcubeServices = new FlexcubeServices();
        $balance = $flexcubeServices->AccountBalance($account, $branch);
        if (!$balance) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        return $this->getResponse([
            'account' => $account,
            'branch' => $balance->{'BRANCH_CODE'},
            'opening_valance' => $balance->{'OPNBAL'},
            'current_balance' => $balance->{'CURBAL'},
            'available_balance' => $balance->{'AVLBAL'},
            'block_amount' => $balance->{'ACY_BKD_AMT'},
        ]);
    }

    public function getAccountDetails(): object
    {
        // TODO: Implement getAccountDetails() method.
        return  $this->getResponse();
    }

    public function getCustomerDetailsByCif(): object
    {
        return $this->getResponse();
    }
}
