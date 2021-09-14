<?php

namespace Database\Seeders;

use App\Models\Accounting;
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
        Accounting::truncate();
        TransactionItem::truncate();
        TransactionLog::truncate();
        Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        $case = null;
        switch($case){
            case 1:
                $start_date = date("Y-m-d H:i:s", strtotime('2021-09-01 00:00:00'));
                for($i = 0; $i < 100; $i++){
                    $duration = rand(1, 5);
                    $end_date = date("Y-m-d H:i:s", strtotime($start_date.' +'.$duration.' days'));

                    \DB::transaction(function () use ($start_date, $end_date, $i) {
                        $store = \App\Models\Store::first();

                        $status = 'booking';
                        if($start_date < date("Y-m-d H:i:s")){
                            if($end_date > date("Y-m-d H:i:s")){
                                $status = 'process';
                            } else {
                                $status = 'complete';
                            }
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
                break;
            case 2:
                $invoiceNumber = 1;
                $start_date = date("Y-m-d H:i:s", strtotime("2020-01-01 00:00:00"));

                do {
                    // Configuration (Time)
                    $transactionPerDay = rand(0, 5);
                    $availableTime = ['days', 'hours'];
                    $nextTime = $availableTime[array_rand($availableTime)];
                    // Get Next Time
                    $nextValue = $nextTime == 'days' ? rand(1, 7) : rand(0, 24);
                    // Next Date
                    $next_date = date("Y-m-d H:i:s", strtotime($start_date." +".$nextValue." ".$nextTime));
                    // End Date
                    $end_date = date("Y-m-d H:i:s", strtotime($next_date." -1 hours"));

                    // Generate Transaction
                    for($i = 0; $i <= $transactionPerDay; $i++){
                        // Get Store
                        $store = \App\Models\Store::inRandomOrder()->first();
                        // Invoice
                        $prefix = 'INVC';
                        $date = date('dmy');
                        $timestamp = str_pad($invoiceNumber, 10, '0', STR_PAD_LEFT);;
                        $invoice = ($prefix.'/'.$date.'/'.$store->invoice_prefix.'/'.$timestamp);

                        // Get Product
                        $availableProduct = $store->productDetail()->select('product_id')->groupBy('product_id')->get();
                        $productItem = rand(1, count($availableProduct));
                        $transactionItem = [];
                        $transactionItemArr = [];
                        $price = 0;
                        for($item = 0; $item < $productItem; $item++){
                            $product = \App\Models\ProductDetail::where('store_id', $store->id)
                                ->whereNotIn('serial_number', $transactionItem)
                                ->inRandomOrder()
                                ->first();

                            $transactionItemArr[] = new \App\Models\TransactionItem([
                                'product_id' => $product->product_id,
                                'product_detail_id' => $product->id,
                                'price' => $product->product->price,
                                'discount' => 0,
                                'note' => null
                            ]);
                            $transactionItem[] = $product->serial_number;
                            $price += $product->product->price;
                        }

                        \DB::transaction(function () use ($store, $invoice, $start_date, $end_date, $price, $transactionItemArr) {
                            // Generate Transaction
                            $transaction = new \App\Models\Transaction();
                            $transaction->user_id = 1;
                            $transaction->store_id = $store->id;
                            $transaction->customer_id = 1;
                            $transaction->invoice = $invoice;
                            $transaction->date = date("Y-m-d H:i:s", strtotime($start_date));
                            $transaction->start_date = date("Y-m-d H:i:s", strtotime($start_date));
                            $transaction->end_date = date("Y-m-d H:i:s", strtotime($end_date));
                            $transaction->must_end_date = date("Y-m-d H:i:s", strtotime($end_date));
                            $transaction->back_date = date("Y-m-d H:i:s", strtotime($end_date));
                            $transaction->amount = $price;
                            $transaction->paid = rand(0, 100) * $price / 100;
                            $transaction->charge = 0;
                            $transaction->extra = 0;
                            $transaction->status = "complete";
                            $transaction->note = null;
                            $transaction->save();

                            // Transaction Item
                            if(!empty($transactionItemArr)){
                                $transaction->transactionItem()->saveMany($transactionItemArr);
                            }
                            // Transaction Log
                            $transaction->transactionLog()->saveMany([
                                new \App\models\TransactionLog([
                                    'user_id' => 1,
                                    'date' => date("Y-m-d H:i:s", strtotime($start_date)),
                                    'log' => 'Just data testing'
                                ])
                            ]);
                        });

                        $invoiceNumber++;
                    }
                    $start_date = $next_date;
                } while(date("Y-m-d", strtotime($start_date)) <= date("Y-m-d"));
                break;
        }
    }
}
