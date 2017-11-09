<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendResetPasswordMail implements ShouldQueue
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
        Mail::send('emails.resetpassword',['user'=>$user,'token'=>$token],function($message) use ($mail) {
            $message->to($mail)->subject('重置密码');
        });
    }
}
