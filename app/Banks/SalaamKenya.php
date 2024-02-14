<?php

namespace Noorfarooqy\BankGateway\Banks;

use Noorfarooqy\NoorAuth\Traits\ResponseHandler;

class SalaamKenya extends Bank
{
    use ResponseHandler;
    public function getBalance($account, $branch = null): object
    {
        // TODO: Implement getBalance() method.
        return $this->getResponse();
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
