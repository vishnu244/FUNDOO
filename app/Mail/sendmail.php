<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\passwordReset;

class sendmail extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $email;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this -> markdown('reset')->with([
            'token' => $this -> token,
            'email' => $this -> email
        ]);
    }
}
