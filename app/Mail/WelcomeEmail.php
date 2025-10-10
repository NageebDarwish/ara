<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $htmlContent;
    public $emailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct($htmlContent, $subject)
    {
        $this->htmlContent = $htmlContent;
        $this->emailSubject = $subject;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->html($this->htmlContent);
    }
}
