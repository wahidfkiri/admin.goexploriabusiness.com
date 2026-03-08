<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $passwordPlain;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $passwordPlain)
    {
        $this->user = $user;
        $this->passwordPlain = $passwordPlain;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenue sur ' . config('app.name'))
                    ->view('emails.welcome_user');
    }
}
