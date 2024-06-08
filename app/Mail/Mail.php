<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Mail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.welcome',
        );
    }
    public function build(){
        return $this->view(view:'mails.welcome');
    }

    public function attachments(): array
    {
        return [];
    }
}
