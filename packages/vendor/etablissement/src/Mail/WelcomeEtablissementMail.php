<?php

namespace Vendor\Etablissement\Mail;

use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEtablissementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $etablissement;

    public function __construct(User $user, $password, Etablissement $etablissement)
    {
        $this->user = $user;
        $this->password = $password;
        $this->etablissement = $etablissement;
    }

    public function build()
    {
        return $this->subject('Bienvenue - Accès à votre compte')
            ->view('etablissement::emails.welcome_etablissement');
    }
}