<?php

namespace Vendor\MailMarketing\Mail;

use App\Services\MailThemeService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $theme;
    protected $mailThemeService;

    public function __construct($data, $theme = 'modern')
    {
        $this->data = $data;
        $this->theme = $theme;
        $this->mailThemeService = app(MailThemeService::class);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['subject'],
        );
    }

    public function content(): Content
    {
        // Utiliser le service de thème pour générer le contenu
        $html = $this->mailThemeService->render($this->theme, [
            'subject' => $this->data['subject'],
            'content' => $this->data['content'],
            'tracking' => $this->data['tracking'],
            'prenom' => $this->data['prenom'] ?? null,
            'nom' => $this->data['nom'] ?? null,
            'ctaUrl' => $this->data['ctaUrl'] ?? null,
            'ctaText' => $this->data['ctaText'] ?? null,
            'features' => $this->data['features'] ?? null,
            'stats' => $this->data['stats'] ?? null,
            'highlights' => $this->data['highlights'] ?? null,
            'timeline' => $this->data['timeline'] ?? null,
            'testimonial' => $this->data['testimonial'] ?? null,
            'socialLinks' => $this->data['socialLinks'] ?? null,
            'logo' => $this->data['logo'] ?? null,
            'headerSubtitle' => $this->data['headerSubtitle'] ?? null,
        ]);

        return new Content(
            html: $html,
        );
    }
}