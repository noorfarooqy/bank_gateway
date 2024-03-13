<?php

namespace Noorfarooqy\BankGateway\Banks;

use Illuminate\Support\Facades\Log;
use Noorfarooqy\Flexcube\Services\FlexcubeServices;
use Noorfarooqy\NoorAuth\Traits\ResponseHandler;

class SalaamKenya extends Bank
{
    use ResponseHandler;
    public function getBalance($account, $branch = null): object
    {
        Log::info('Bank gateway ');
        $flexcubeServices = new FlexcubeServices();
        $balance = $flexcubeServices->AccountBalance($account, $branch);
        if (!$balance) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }

        $response = [
            'account' => $account,
            'ccy' => $balance->{'CCY'},
            'branch' => $balance->{'BRANCH_CODE'},
            'opening_valance' => $balance->{'OPNBAL'},
            'current_balance' => $balance->{'CURBAL'},
            'available_balance' => $balance->{'AVLBAL'},
            'block_amount' => $balance->{'ACY_BKD_AMT'},
        ];
        Log::info('Bank gateway ');
        $response = $this->getResponse($response);
        Log::info(json_encode($response));
        return $response;
    }

    public function getAccountDetails($account, $branch = null): object
    {

        $flexcubeServices = new FlexcubeServices();
        $branch = $branch ?? substr($account, 0, 3);
        $details = $flexcubeServices->AccountDetails($account, $branch);
        if (!$details) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        $acc = [
            'branch' => $details->{'BRN'},
            'account' => $details->{'ACC'},
            'cif_no' => $details->{'CUSTNO'},
            'class' => $details->{'ACCLS'},
            'ccy' => $details->{'CCY'},
            'name' => $details->{'CUSTNAME'},
            'description' => $details->{'ADESC'},
            'is_frozen' => $details?->{'FROZEN'} == 'Y',
            'address_one' => $details?->{'ADDRESS_1'} ?? '',
            'address_two' => $details?->{'ADDRESS_2'} ?? '',
            'address_three' => $details?->{'ADDRESS_3'} ?? '',
            'address_four' => $details?->{'ADDRESS_4'} ?? '',
            'status' => $details->{'ACCSTAT'} ?? '',
            'is_dormant' => $details->{'DORMNT'} ?? true,
        ];
        $this->setError('', 0);
        $this->setSuccess('success');
        return  $this->getResponse($acc);
    }

    public function getCustomerDetailsByCif(): object
    {
        return $this->getResponse();
    }

    public function getExchangeRate($from, $to, $branch = null): object
    {
        $branch = $branch ?? "000";

        $flexcubeServices = new FlexcubeServices();
        $rate = $flexcubeServices->ExchangeRate($from, $to, $branch);
        if (!$rate) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        return $this->getResponse([
            'branch' => $rate->{'BRNCD'},
            'from' => $rate->{'CCY1'},
            'to' => $rate->{'CCY2'},
            'type' => $rate?->{'Ccy-Rate-Details'}[0]?->{'RATETYPE'} ?? '',
            'midrate' => $rate?->{'Ccy-Rate-Details'}[0]?->{'MIDRATE'} ?? '',
            'buyrate' => $rate?->{'Ccy-Rate-Details'}[0]?->{'BUYRATE'} ?? '',
            'salerate' => $rate?->{'Ccy-Rate-Details'}[0]?->{'SALERATE'} ?? '',
        ]);
    }
    public function getTransactionCharge($amount, $transaction_type): float
    {
        switch ($transaction_type) {
            case 'sch':
                # code...
                return 0;
            default:
                # code...
                return 0;
        }
    }

    public function blockAmount($account, $branch, $amount, $hp_code, $ref, $expires_at = null)
    {
        $expires_at = $expires_at ?? now()->addDay();
        $flexcubeServices = new FlexcubeServices();
        $block = $flexcubeServices->AccountBlockAmount($account, $branch, $amount, $hp_code, $ref, $expires_at);
        if (!$block) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        return $this->getResponse([
            'reference' => $ref,
        ]);
    }
    public function closeBlockAmount($account_number, $ref)
    {
        $flexcubeServices = new FlexcubeServices();
        $block = $flexcubeServices->QueryAmountBlock($account_number, $ref);
        if (!$block) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        Log::info('---Block info----');
        Log::info(json_encode($block));
        if ($block?->{'Amount-Blocks-Full'}?->{'ACC'} != $account_number) {
            $this->setError('Account number does not match with block reference given');
            return $this->getResponse();
        }
        $closed_block = $flexcubeServices->AccountUnblockAmount($ref);
        if (!$closed_block) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        return $this->queryAmountBlock($account_number, $ref); // fetch the updated status of the block amount
    }
    public function getAccountAmountBlocks($account_number)
    {
        $flexcubeServices = new FlexcubeServices();
        $amount_blocks = $flexcubeServices->GetAccountAmountBlocks($account_number);
        if (!$amount_blocks) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        if (!isset($amount_blocks?->{'Account-Full'}?->{'Amount-Block'}) || !is_array($amount_blocks?->{'Account-Full'}?->{'Amount-Block'})) {
            $amount_blocks->{'Account-Full'}->{'Amount-Block'} = isset($amount_blocks?->{'Account-Full'}?->{'Amount-Block'}) ? [$amount_blocks->{'Account-Full'}?->{'Amount-Block'}] : [];
        }
        $blocks = [];
        foreach ($amount_blocks->{'Account-Full'}->{'Amount-Block'} as $key => $block) {
            $blocks[] = [
                'account' => $amount_blocks->{'Account-Full'}->{'ACCOUNT'},
                'branch' => $amount_blocks->{'Account-Full'}->{'BRANCH'},
                'desc' => $amount_blocks->{'Account-Full'}->{'AC_DESC'},
                'amount' => $block->{'AMOUNT'},
                'block_no' => $block->{'AMOUNT_BLOCK_NO'},
                'block_type' => $block->{'AMOUNT_BLOCK_TYPE'},
                'reference_no' => $block->{'AMOUNT_BLOCK_NO'},
                'hp_code' => $block->{'HOLDCODE'},
                'effective_date' => $block->{'EFFECTIVE_DATE'},
                'description' => $block->{'HOLDDESC'},
            ];
        }
        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse($blocks);
    }
    public function queryAmountBlock($account_number, $block_no)
    {
        $flexcubeServices = new FlexcubeServices();
        $block = $flexcubeServices->QueryAmountBlock($account_number, $block_no);
        if (!$block) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        return $this->getResponse([
            'account' => $block?->{'Amount-Blocks-Full'}?->{'ACC'},
            'branch' => $block?->{'Amount-Blocks-Full'}?->{'BRANCH'},
            'amount' => $block?->{'Amount-Blocks-Full'}?->{'AMT'},
            'block_no' => $block?->{'Amount-Blocks-Full'}?->{'AMTBLKNO'},
            'block_type' => $block?->{'Amount-Blocks-Full'}?->{'ABLKTYPE'},
            'reference_no' => $block?->{'Amount-Blocks-Full'}?->{'REFERENCE_NO'},
            'hp_code' => $block?->{'Amount-Blocks-Full'}?->{'HPCODE'},
            'effective_date' => $block?->{'Amount-Blocks-Full'}?->{'EFFDATE'},
            'description' => $block?->{'Amount-Blocks-Full'}?->{'HOLDDESC'},
        ]);
    }

    public function createTransaction($amount, $product, $origin, $offset = null)
    {
        $flexcubeServices = new FlexcubeServices();
        $transaction = $flexcubeServices->AccountTransaction($amount, $product, $origin, $offset);
        if (!$transaction) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse($transaction);
    }
}
