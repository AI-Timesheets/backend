<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRecoveryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $recoveryLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recoveryLink)
    {
        $this->recoveryLink = $recoveryLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.recovery');
    }
}
