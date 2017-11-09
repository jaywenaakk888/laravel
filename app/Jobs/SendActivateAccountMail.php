<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendActivateAccountMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $mail;
    protected $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->user = $value['user'];
        $this->mail = $value['mail'];
        $this->token = $value['token'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $mail = $this->mail;
        $token = $this->token;
        Mail::send('emails.activateaccount', ['user'=>$user,'token'=>$token], function ($message) use ($mail) {
            $message->to($mail)->subject('账号激活');
        });
    }
}
