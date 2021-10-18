<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;

class TransactionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind Customer about on-going transaction';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get Running Transaction
        $data = \App\Models\Transaction::whereNotIn('status', ['complete', 'cancel'])
            ->orderBy('date', 'asc')
            ->chunk(50, function($transactions){
                foreach($transactions as $transaction){
                    $logStatus = '';
                    $logType = '';
                    $logExtra = [];
                    $mailable = null;

                    // Check if Status is Booking or Process
                    if($transaction->status == "process"){
                        $logType = 'rent';

                        // Transaction is Process, check if current date is near end date
                        $currDate = date("Y-m-d H:i:s");
                        $endDate = date("Y-m-d H:i:s", strtotime($transaction->end_date));
                        // Check diff
                        $diffTime = strtotime($endDate) - strtotime($currDate);
                        $diffTimeInMinutes = round($diffTime / 60, 2);
                        if($diffTimeInMinutes < 0){
                            // Late
                            $logStatus = 'late';
                            $mailable = new \App\Mail\Transaction\Reminder\ProcessLate($transaction);
                        } else {
                            // Near End Date, item must be returned
                            $logStatus = 'near end';
                            $mailable = new \App\Mail\Transaction\Reminder\ProcessNear($transaction);
                        }

                        $logExtra = [
                            'currDate' => $currDate,
                            'endDate' => $endDate,
                            'diffTime' => $diffTime,
                            'diffTimeInMinutes' => $diffTimeInMinutes
                        ];
                    } else {
                        $logType = 'booking';
                    }

                    // Send an Email
                    if(!empty($transaction->customer->email) && !empty($mailable)){
                        $mail = $transaction->customer->email;
                        $mailJob = dispatch(new \App\Jobs\SendEmailJob($mail, $mailable))
                            ->delay(\Carbon\Carbon::now()->addSeconds(10)); // Add some delay
                    }
                    \Log::debug("Check on Transaction Reminder Command ~ \App\Console\Command\TransactionReminder@handle", [
                        'transaction' => $transaction,
                        'items' => $transaction->transactionItem,
                        'logStatus' => $logStatus,
                        'logType' => $logType,
                        'logExtra' => $logExtra
                    ]);
                }
            });
        return 0;
    }
}
