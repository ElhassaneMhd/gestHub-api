<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;

    public function __construct($subject,$message) {
        $this->subject = $subject;
        $this->message = $message;
    }

   public function build()
    {
        return $this->view('mails.welcome')
                    ->with([
                        'subject' => $this->subject,
                        'message' => $this->message,
                    ]);
    }
    public function attachments(): array
    {
        return [];
    }
}
