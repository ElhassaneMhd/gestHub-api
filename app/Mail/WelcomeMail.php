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
    public $data;
    public $pdfPath;
    public function __construct($data) {
        $this->data = $data;
        $this->subject = $data['subject'];
        $this->pdfPath = $data['pdfPath']??null;
    }
       public function envelope(): Envelope
    {
        return new Envelope(
            subject:  $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.welcome',
        );
    }
    public function attachments(): array
    {
        if (!$this->pdfPath) {
            return [];
        }
        dump($this->pdfPath);
       return [
            Attachment::fromPath('https://gesthub.netlify.app/assets/'.$this->pdfPath)
                      ->as('GestDoc.pdf')
                      ->withMime('application/pdf'),
        ];
    }
}
