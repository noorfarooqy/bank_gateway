<?php

namespace Noorfarooqy\BankGateway\Banks;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Noorfarooqy\Flexcube\Services\FlexcubeServices;
use Noorfarooqy\Flexcube\Services\ReportsServices;
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
        return $this->getResponse($acc);
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

    public function blockAmount($account, $amount, $hp_code, $ref, $branch = null, $expires_at = null)
    {
        $expires_at = $expires_at ?? now()->addDay();
        $flexcubeServices = new FlexcubeServices();
        $block = $flexcubeServices->AccountBlockAmount($account, $branch ?? substr($account, 0, 3), $amount, $hp_code, $ref, $expires_at);
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
        if ($block?->{'ACC'} != $account_number) {
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
            'account' => $block?->{'ACC'},
            'branch' => $block?->{'BRANCH'},
            'amount' => $block?->{'AMT'},
            'block_no' => $block?->{'AMTBLKNO'},
            'block_type' => $block?->{'ABLKTYPE'},
            'reference_no' => $block?->{'REFERENCE_NO'},
            'hp_code' => $block?->{'HPCODE'},
            'effective_date' => $block?->{'EFFDATE'},
            'description' => $block?->{'HOLDDESC'},
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
        $transaction_details = [
            'xref' => $transaction?->{'XREF'},
            'fccref' => $transaction?->{'FCCREF'},
            'rate' => $transaction?->{'XRATE'} ?? 1,
            'amoount' => $transaction?->{'LCYAMT'} ?? '',
            'offset_amount' => $transaction?->{'OFFSETAMT'} ?? '',
            'offset_account' => $transaction?->{'OFFSETACC'} ?? '',
            'offset_ccy' => $transaction?->{'OFFSETCCY'} ?? '',
            'offset_branch' => $transaction?->{'OFFSETBRN'} ?? '',
            'trn_date' => $transaction?->{'TXNDATE'} ?? '',
            'value_date' => $transaction?->{'VALDATE'} ?? '',
        ];
        return $this->getResponse($transaction_details);
    }

    public function transactionDetails($fcc_reference)
    {
        $flexcubeServices = new FlexcubeServices();
        $transaction = $flexcubeServices->AccountQueryTransaction($fcc_reference);
        if (!$transaction) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        $this->setError('', 0);
        $this->setSuccess('success');
        $transaction_details = [
            'xref' => $transaction?->{'XREF'},
            'fccref' => $transaction?->{'CONTREFNO'},
            'rate' => $transaction?->{'XRATE'} ?? 1,
            'amoount' => $transaction?->{'LCYAMT'} ?? '',
            'offset_amount' => $transaction?->{'OFFSETAMT'} ?? '',
            'offset_account' => $transaction?->{'OFFSETACC'} ?? '',
            'description' => $transaction?->{'TRNCDDESC'} ?? '',
            'trn_date' => $transaction?->{'TXNDATE'} ?? '',
        ];
        return $this->getResponse($transaction_details);
    }

    public function reverseTransaction($fcc_reference)
    {
        $flexcubeServices = new FlexcubeServices();
        $transaction = $flexcubeServices->AccountReverseTransaction($fcc_reference);
        if (!$transaction) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse(['fcc_ref' => $fcc_reference]);
    }

    public function getCustomerDetailsByCif($customer_cif): object
    {
        $flexcubeServices = new FlexcubeServices();
        $transaction = $flexcubeServices->CustomerDetails($customer_cif);
        if (!$transaction) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        $this->setError('', 0);
        $this->setSuccess('success');
        $transaction = $transaction?->{'Stvws-Stdcifqy'};
        $transaction_details = [
            'cif' => $transaction?->{'CUSTOMER_ID'},
            'email' => $transaction?->{'EMAIL'},
            'first_name' => $transaction?->{'FIRST_NAME'} ?? 1,
            'last_name' => $transaction?->{'LAST_NAME'} ?? '',
            'gender' => $transaction?->{'GENDER'} ?? '',
            'title' => $transaction?->{'TITLE'} ?? '',
            'short_name' => $transaction?->{'SHORT_NAME'} ?? '',
            'address_line_1' => $transaction?->{'ADDRESS_LINE1'} ?? '',
            'address_line_2' => $transaction?->{'ADDRESS_LINE2'} ?? '',
            'address_line_3' => $transaction?->{'ADDRESS_LINE3'} ?? '',
            'address_line_4' => $transaction?->{'ADDRESS_LINE4'} ?? '',
            'address_country' => $transaction?->{'ADDRESS_COUNTRY'} ?? '',
            'mobile_number' => $transaction?->{'MOBILE_NO'} ?? '',
            'is_verified' => $transaction?->{'IS_VERIFIED'} ?? '',
            'nationality' => $transaction?->{'NAITONALITY'} ?? '',
            'unique_id_name' => $transaction?->{'UNIQUE_ID_NAME'} ?? '',
            'unique_id_value' => $transaction?->{'UNIQUE_ID_VALUE'} ?? '',
            'created_at' => $transaction?->{'CREATED_AT'} ?? '',
            'customer_type' => $transaction?->{'CUSTOMER_TYPE'} ?? '',
            'customer_category' => $transaction?->{'CATEGORY'} ?? '',
        ];
        return $this->getResponse($transaction_details);
    }

    public function getCustomerAccounts($customer_cif, $ccy = null): object
    {

        $flexcubeServices = new FlexcubeServices();
        $customer_accounts = $flexcubeServices->CustomerAccounts($customer_cif);
        if (!$customer_accounts) {
            $this->setError($flexcubeServices->getMessage());
            return $this->getResponse();
        }
        $accounts = $customer_accounts?->{'Stvws-Stdaccqy'} ?? [];

        if (!is_array($accounts) && $accounts != null) {
            $accounts = [$accounts];
        }
        $cust_accounts = [];
        foreach ($accounts as $key => $account) {
            if ($ccy != null && $account?->{'CCY'} != $ccy) {
                continue;
            }
            $cust_accounts[] = [
                'account_type' => $account?->{'ACCOUNT_TYPE'},
                'account_status' => $account?->{'ACC_STATUS'},
                'account_desc' => $account?->{'AC_DESC'},
                'account_open_date' => $account?->{'AC_OPEN_DATE'},
                'account_branch' => $account?->{'BRANCH_CODE'},
                'account_ccy' => $account?->{'CCY'},
                'account_number' => $account?->{'CUST_AC_NO'},
            ];
        }
        $this->setError('', 0);
        $this->setSuccess('success');

        return $this->getResponse($cust_accounts);
    }

    public function getCustomerAccountStatement($account_number, $start_date, $end_date, $format = 'pdf', $json = true)
    {
        $reportServices = new ReportsServices();

        $statement = $reportServices->AccountStatementReport($account_number, $start_date, $end_date, $format);
        if (!$statement) {
            $this->setError($reportServices->getMessage());
            return $json ? $this->getResponse() : false;
        }

        $base64_file = $statement?->runReportReturn;
        info('----REPOIRT RESPOSE-----');
        // Log::info(json_encode($base64_file));
        if ($base64_file == null) {
            $this->setError(env('APP_DEBUG') ? json_encode($statement) : 'Statement response type is not valid');
            return $json ? $this->getResponse() : false;
        }
        $decoded_file = base64_decode($base64_file?->reportBytes);
        if ($decoded_file == null) {
            $this->setError(env('APP_DEBUG') ? json_encode($base64_file) : 'Decoded statement response type is not valid');
            return $json ? $this->getResponse() : false;
        }
        $statement_name = $account_number . '_' . str_replace('/', '_', $start_date) . '_' . str_replace('/', '_', $end_date) . '.' . $format;
        $path = 'statements/';
        Storage::disk('public')->put($path . $statement_name, $decoded_file);
        Storage::disk('public')->put($statement_name . '.txt', json_encode($base64_file));

        $this->setError('', 0);
        $this->setSuccess('success');
        $statement = [
            'statement_link' => $json ? Storage::disk('public')->url($path . $statement_name) : Storage::disk('public')->path($path . $statement_name),
            'from_date' => $start_date,
            'to_date' => $end_date,
            'account_number' => $account_number
        ];
        return $json ? $this->getResponse($statement) : $statement;


    }
}
