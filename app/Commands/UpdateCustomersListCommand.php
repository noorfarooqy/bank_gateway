<?php

namespace Noorfarooqy\BankGateway\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Noorfarooqy\BankGateway\Imports\BgCustomersImport;
use Noorfarooqy\NoorAuth\Traits\Helper;

class UpdateCustomersListCommand extends Command
{
    use Helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bg:import_customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from core banking system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->title('Starting import');
        $gateway_key = config('bankgateway.configured_gateway');
        $bank_class = config('bankgateway.bank_gateways')[$gateway_key];
        $bank = new $bank_class;

        $from_date = config('bankgateway.reports.static.from_date') ?? now()->subMonth()->firstOfMonth()->format('d-M-Y');
        $to_date = now()->subMonth()->endOfMonth()->format('d-M-Y');
        $customers = $bank->getCustomersListReport($from_date, $to_date, false);

        $file_path = Storage::disk('public')->path($customers['file_path']);

        Excel::import(new BgCustomersImport, $file_path, null, \Maatwebsite\Excel\Excel::CSV);
        $this->output->success('Import successful');

    }
}
