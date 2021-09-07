<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        if($transaction->paid != 0){
            $prev_accounting = \App\Models\Accounting::where('store_id', $transaction->store_id)
                ->orderBy('created_at', 'desc')
                ->first();

            \App\Models\Accounting::create([
                'store_id' => $transaction->store_id,
                'user_id' => \Auth::user()->id ?? 1,
                'transaction_id' => $transaction->id,
                'type' => 'income',
                'amount' => $transaction->paid,
                'sum_amount' => !empty($prev_accounting) ? $prev_accounting->sum_amount + $transaction->paid : $transaction->paid,
                'created_at' => $transaction->date
            ]);
        }
    }

    /**
     * Handle the Transaction "updating" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updating(Transaction $transaction)
    {
        $prevValue = $transaction->getOriginal('paid');
        $currValue = $transaction->paid;

        if($prevValue != $currValue){
            $prev_accounting = \App\Models\Accounting::where('store_id', $transaction->store_id)
                ->orderBy('created_at', 'desc')
                ->first();

            $type = 'income';
            $calc = ($transaction->paid - $prevValue);
            $calcSum = !empty($prev_accounting) ? $prev_accounting->sum_amount + $calc : $calc;
            if($prevValue > $currValue){
                $type = 'outcome';
                $calc = ($prevValue - $transaction->paid);
                $calcSum = !empty($prev_accounting) ? $prev_accounting->sum_amount - ($calc) : $calc;
            }

            \App\Models\Accounting::create([
                'store_id' => $transaction->store_id,
                'user_id' => \Auth::user()->id,
                'transaction_id' => $transaction->id,
                'type' => $type,
                'amount' => $calc,
                'sum_amount' => $calcSum
            ]);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
