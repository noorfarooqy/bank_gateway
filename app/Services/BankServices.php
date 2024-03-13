<?php

namespace Noorfarooqy\BankGateway\Services;

use Noorfarooqy\NoorAuth\Services\NoorServices;

class BankServices extends NoorServices
{
    public $has_failed;
    public function getAccountBalance($request)
    {

        $this->request = $request;
        $this->rules = [
            'account_number' => 'required|numeric',
            'branch_code' => 'nullable|numeric',
        ];

        $this->customValidate();

        if ($this->has_failed) {
            return $this->getResponse();
        }

        $data = $this->validatedData();

        $gateway_key = config('bankgateway.configured_gateway');
        $bank_class = config('bankgateway.bank_gateways')[$gateway_key];
        $bank = new $bank_class;

        return $bank->getBalance($data['account_number'], isset($data['branch_code']) ? $data['branch_code'] : null);
    }

    public function getAccountBlockAmounts($request)
    {
        $this->request = $request;
        $this->rules = [
            'account_number' => 'required|numeric'
        ];

        $this->customValidate();

        if ($this->has_failed) {
            return $this->getResponse();
        }

        $data = $this->validatedData();

        $gateway_key = config('bankgateway.configured_gateway');
        $bank_class = config('bankgateway.bank_gateways')[$gateway_key];
        $bank = new $bank_class;

        return $bank->getAccountAmountBlocks($data['account_number']);
    }

    public function getAccountDetails($request)
    {
        $this->request = $request;
        $this->rules = [
            'account_number' => 'required|numeric'
        ];

        $this->customValidate();

        if ($this->has_failed) {
            return $this->getResponse();
        }

        $data = $this->validatedData();

        $gateway_key = config('bankgateway.configured_gateway');
        $bank_class = config('bankgateway.bank_gateways')[$gateway_key];
        $bank = new $bank_class;

        return $bank->getAccountDetails($data['account_number']);
    }
    public function AccountBlockAmount($request)
    {
        $this->request = $request;

        $this->rules = [
            'amount' => 'required|numeric',
            'account' => 'required|exists:onboarded_customers,account_number',
            'expires_at' => 'nullable|date|after:now',
            'block_code' => 'required|string|max:12',
            'block_ref' => 'required|string|max:45',
        ];
        $this->customValidate();
        if ($this->has_failed) {
            return $this->getResponse();
        }

        $data = $this->validatedData();

        $gateway_key = config('bankgateway.configured_gateway');
        $bank_class = config('bankgateway.bank_gateways')[$gateway_key];
        $bank = new $bank_class;

        return $bank->blockAmount($data['account'], $data['amount'], $data['block_code'], $data['block_ref'], $expires_at = $request->expires_at);
    }
}
