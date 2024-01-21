<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationUserMail;


class SendRegistrationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $email_user;
    protected $password;

    public function __construct($email, $email_user, $password)
    {
        $this->email = $email;
        $this->email_user = $email_user;
        $this->password = $password;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new RegistrationUserMail($this->email_user, $this->password));
    }

}
