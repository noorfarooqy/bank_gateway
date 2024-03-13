<?php

namespace Noorfarooqy\BankGateway\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\BankGateway\Services\BankServices;

class ApiController extends Controller
{
    public function getAccountBalance(Request $request, BankServices $bankServices)
    {
        return $bankServices->getAccountBalance($request);
    }

    public function getAccountDetails(Request $request, BankServices $bankServices)
    {
        return $bankServices->getAccountDetails($request);
    }
    public function getAccountBlockAmounts(Request $request, BankServices $bankServices)
    {
        return $bankServices->getAccountBlockAmounts($request);
    }

    public function postAccountDebitTransaction(Request $request, BankServices $bankServices)
    {
    }

    public function postAccountCreditTransaction(Request $request, BankServices $bankServices)
    {
    }

    public function postAccountReverseTransaction(Request $request, BankServices $bankServices)
    {
    }

    public function postAccountBlockAmounts(Request $request, BankServices $bankServices)
    {
        return $bankServices->AccountBlockAmount($request);
    }

    public function postAccountBlocksAmountsClose(Request $request, BankServices $bankServices)
    {
        return $bankServices->AccountBlocksAmountsClose($request);
    }
}
