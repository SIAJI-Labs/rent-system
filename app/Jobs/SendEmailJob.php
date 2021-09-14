<?php

namespace App\Jobs;

use Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $mailable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    public $backoff = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $mailable)
    {
        $this->email = $email;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Mail::to($this->email)->send($this->mailable);
        } catch(\Exception $e){
            \Log::error("Something went wrong when sending an email via SendEmailJob ~ app\Jobs\SendEmailJob@handle", [
                'email' => $this->email,
                'event' => $this->mailable,
                'config' => [
                    'from_name' => env('MAIL_FROM_NAME'),
                    'from_address' => env('MAIL_FROM_ADDRESS'),
                    'host' => env('MAIL_HOST'),
                    'driver' => env('MAIL_DRIVER'),
                    'encryption' => env('MAIL_ENCRYPTION')
                ],
                'exception' => $e
            ]);
        }
    }
}
