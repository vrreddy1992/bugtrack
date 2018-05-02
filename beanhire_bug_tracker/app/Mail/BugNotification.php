<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BugNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bugData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bugData)
    {
        $this->bugData = $bugData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->bugData['subject'])
                    ->view('BugNotification');
    }
}
