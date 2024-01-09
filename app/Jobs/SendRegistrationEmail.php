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
    protected $cpf;
    protected $password;

    public function __construct($email, $cpf, $password)
    {
        $this->email = $email;
        $this->cpf = $cpf;
        $this->password = $password;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new RegistrationUserMail($this->cpf, $this->password));
    }

}
