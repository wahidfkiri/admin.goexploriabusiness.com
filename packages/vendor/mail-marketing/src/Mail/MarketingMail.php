<?php

namespace Vendor\MailMarketing\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $tracking;

    public function __construct($data)
    {
        $this->data = $data;
        $this->tracking = $data['tracking'] ?? [];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail-marketing::emails.marketing',
            with: [
                'content' => $this->data['content'],
                'tracking' => $this->tracking,
            ],
        );
    }
}