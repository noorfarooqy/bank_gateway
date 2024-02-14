<?php

namespace Noorfarooqy\BankGateway\Banks;

/**
 * Abstract Bank class
 * 
 * @author Noorfarooqy
 */
abstract class Bank
{
    /**
     * @var Customer 
     */
    private $customer;

    /**
     * Get balance of the bank account
     * 
     * @return float
     */
    abstract public function getBalance($account, $branch = null): object;

    /**
     * Get account details
     * 
     * @return array
     */
    abstract public function getAccountDetails(): object;

    /**
     * Get customer id details
     * 
     * @return array
     */
    abstract public function getCustomerDetailsByCif(): object;
}
