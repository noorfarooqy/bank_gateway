<?php

use Illuminate\Support\Facades\Route;
use Noorfarooqy\BankGateway\Controllers\ApiController;

Route::group(['prefix' => '/v1/bg', 'as' => 'v1.banks.'], function () {

    Route::group(['prefix' => '/account', 'as' => 'account.'], function () {
        Route::post('/balance', [ApiController::class, 'getAccountBalance'])->name('balance');
        Route::post('/details', [ApiController::class, 'getAccountDetails'])->name('details');
        Route::post('/block/amounts', [ApiController::class, 'getAccountBlockAmounts'])->name('block.amounts');

        Route::post('/debit', [ApiController::class, 'postAccountDebitTransaction'])->name('debit');
        Route::post('/credit', [ApiController::class, 'postAccountCreditTransaction'])->name('credit');
        Route::post('/reverse', [ApiController::class, 'postAccountReverseTransaction'])->name('reverse');
        Route::post('/block/amounts', [ApiController::class, 'postAccountBlockAmounts'])->name('post.block.amounts');
        Route::post('/blocks/amounts/close', [ApiController::class, 'postAccountBlocksAmountsClose'])->name('post.blocks.amounts.close');
    });
});
