<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $pdfPath;
    public function __construct($subject,$message,$pdfPath =null) {
        $this->subject = $subject;
        $this->message = $message;
        $this->pdfPath = $pdfPath;
    }
       public function envelope(): Envelope
    {
        return new Envelope(
            subject:  $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.welcome',
        );
    }
    public function attachments(): array
    {
        if ($this->pdfPath) {
            return [
                Attachment::fromPath($this->pdfPath)
                          ->as('welcome.pdf')
                          ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
