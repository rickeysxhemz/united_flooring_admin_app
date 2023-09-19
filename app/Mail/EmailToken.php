<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailToken extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mail_data = $mail_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@nail2u.com', 'Nail2U')
            ->with($this->mail_data)
            ->view('mail-templates.send-token');
    }
}
