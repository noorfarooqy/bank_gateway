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
    abstract public function getAccountDetails($account, $branch=null): object;
    abstract public function getCustomerDetailsByCif(): object;
    abstract public function getExchangeRate($from, $to, $branch = null): object;
    abstract public function getTransactionCharge($amount, $transaction_type): float;
    abstract public function blockAmount($account, $branch, $amount, $hp_code, $ref, $expires_at = null);
    abstract public function closeBlockAmount($account_number, $ref);
    abstract public function getAccountAmountBlocks($account_number);
    abstract public function queryAmountBlock($account_number, $block_no);
    abstract public function createTransaction($amount, $product, $origin, $offset = null);
}
