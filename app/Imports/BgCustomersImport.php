<?php

namespace Noorfarooqy\BankGateway\Imports;

use Carbon\Carbon;
use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Noorfarooqy\BankGateway\Models\BgCustomersList;
use Noorfarooqy\NoorAuth\Traits\Helper;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

class BgCustomersImport implements ToCollection, WithStartRow, WithBatchInserts, WithUpserts
{
    use Helper;
    public function startRow(): int
    {
        return 2;
    }
    public function batchSize(): int
    {
        return 1000;
    }
    public function uniqueBy()
    {
        return 'customer_no';
    }
    public function chunkSize(): int
    {
        return 1000;
    }
    public function getConsoleOutput(): OutputStyle
    {
        return new OutputStyle(
            new StringInput(''),
            new StreamOutput(fopen('php://stdout', 'w'))
        );
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $this->debugLog('[*] Importing Customer List');

        foreach ($rows as $key => $row) {
            BgCustomersList::updateOrCreate(
                ['customer_no' => $row[3]],
                [
                    'customer_no' => $row[3],
                    'full_name' => $row[4],
                    'customer_type' => $row[5],
                    'address_line_1' => $row[7],
                    'address_line_2' => $row[8],
                    'address_line_4' => $row[9],
                    'country' => $row[10],
                    'nationality' => $row[11],
                    'branch' => $row[12],
                    'customer_prefix' => $row[15],
                    'date_of_birth' => $row[16],
                    'sex' => $row[17],
                    'mobile_number' => $row[18],
                    'cust_ac_no' => $row[19],
                    'account_class' => $row[20],
                    'status' => $row[21],
                    'national_id' => $row[23],
                    'email' => $row[24],
                    'minor' => $row[25] ?? 'NO',
                    'taxid_no' => $row[26],
                    'nominee_name' => $row[27],
                    'ccy' => $row[28],
                    'ac_open_date' => Carbon::hasFormat('d-M-Y', $row[29]) ? Carbon::createFromFormat('d-M-Y', $row[29])->format('Y-m-d') : null,
                    'passport_no' => $row[30],
                    'pp_exp_date' => ($row[29] != null && Carbon::hasFormat('d-M-Y', $row[29])) ? Carbon::createFromFormat('d-M-Y', $row[29])->format('Y-m-d') : null,
                    'address_line_3' => $row[33],
                ]
            );
        }

    }
}
