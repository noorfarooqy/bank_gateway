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

        return $bank->getBalance($data['account_number'], $data['branch_code']);
    }
}
