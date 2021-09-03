<?php

namespace Database\Seeders;

use Carbon\Carbon;

use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\TransactionItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederTransaction extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TransactionItem::truncate();
        TransactionLog::truncate();
        Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        $start_date = date("Y-m-d H:i:s", strtotime('2021-01-05 00:00:00'));
        for($i = 0; $i < 0; $i++){
            $end_date = date("Y-m-d H:i:s", strtotime($start_date.' +2 days'));

            \DB::transaction(function () use ($start_date, $end_date, $i) {
                $store = \App\Models\Store::first();

                $status = 'booking';
                if($start_date < date("Y-m-d H:i:s")){
                    $status = 'complete';
                }

                $prefix = 'INVC';
                $date = date('dmy');
                $timestamp = str_pad($i, 10, '0', STR_PAD_LEFT);;
                $invoice = ($prefix.'/'.$date.'/'.$store->invoice_prefix.'/'.$timestamp);

                $data = new \App\Models\Transaction();
                $data->user_id = 1;
                $data->store_id = $store->id;
                $data->customer_id = 1;
                $data->invoice = $invoice;
                $data->date = $start_date;
                $data->start_date = $start_date;
                $data->end_date = $end_date;
                $data->must_end_date = $end_date;
                $data->back_date = $status == 'complete' ? $end_date : null;
                $data->amount = 75000;
                $data->discount = 0;
                // $data->paid = $request->store_id;
                // $data->charge = $request->store_id;
                // $data->extra = $request->store_id;
                $data->status = $status;
                $data->note = 'Hanya testing';
                $data->save();

                // Transaction Item
                $data->transactionItem()->saveMany([
                    new \App\Models\TransactionItem([
                        'product_id' => 1,
                        'product_detail_id' => 2,
                        'price' => 75000,
                        'discount' => 0,
                        'note' => 'Catatan testing'
                    ])
                ]);
                // Transaction Log
                $data->transactionLog()->saveMany([
                    new \App\Models\TransactionLog([
                        'user_id' => 1,
                        'date' => $start_date,
                        'log' => 'User membuat data testing via seeder'
                    ])
                ]);
            });

            $start_date = date("Y-m-d H:i:s", strtotime($end_date.' next tuesday'));
        }
    }
}
