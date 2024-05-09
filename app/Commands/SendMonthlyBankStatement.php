<?php

namespace Noorfarooqy\BankGateway\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Noorfarooqy\BankGateway\Mail\SendMonthlyBankStatementEmail;
use Noorfarooqy\EasyNotifications\Services\SendNotificationJob;

class SendMonthlyBankStatement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bg:mbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Monthly Bank Statement';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gateway_key = config('bankgateway.configured_gateway');
        $bank_class = config('bankgateway.bank_gateways')[$gateway_key];
        $bank = new $bank_class;

        $from_date = now()->subMonth()->firstOfMonth()->format('d-M-Y');
        $to_date = now()->subMonth()->endOfMonth()->format('d-M-Y');
        $statement = $bank->getCustomerAccountStatement('0010172338', $from_date, $to_date, 'pdf', false);

        Log::info('sent monthly bank statement');
        Log::info($statement);

        $email = 'mnoor@salaammfbank.co.ke';
        $masked_email = substr($email, 0, 3) . str_repeat('*', strlen(substr($email, 4, strlen($email) - 4))) . substr($email, -4);
        $message = 'Dear Mohamed Noor, your monthly bank statement running  ' . $from_date . ' to ' . $to_date . ' ' .
            'has been sent to your email starting with ' . $masked_email . '. Your view your statement use the password ' . $statement['password'] . PHP_EOL .
            'Thank you for choosing ' . env('APP_NAME');
        $sms_payload = [
            'to' => '0724441724',
            'message' => $message,
        ];
        $statement['account_name'] = 'Mohamed Noor';
        $statement['support_phone_primary'] = '0724441724';
        $statement['support_phone_secondary'] = '0724441724';
        $email_payload = [
            'to' => $email,
            'email_body' => $statement,
            'email_view' => 'bg::mail.consolidated_statement',
            'email_subject' => config('bank_ussd.bank_name') . ' - Monthly Bank Statement',
            'attachments' => [
                [
                    'file' => $statement['statement_link'],
                    'as' => "statement_$from_date" . "_$to_date.pdf",
                    'mime' => 'application/pdf',
                ]
            ],
        ];
        SendNotificationJob::dispatch($sms_payload, 'sms', config('bank_ussd.sms_provider'));
        SendNotificationJob::dispatch($email_payload, 'email');
    }
}
