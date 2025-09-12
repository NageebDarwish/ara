<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsLetter extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    /**
     * Create a new message instance.
     */
    public function __construct($subject,$body)
    {
        $this->subject=$subject;
        $this->body=$body;
    }

     public function build()
    {
        return $this->view('emails.news_letter')
        ->subject($this->subject)
        ->with([
            'subject' => $this->subject,
            'body' => $this->body,
                ]);
    }

}