<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class advertise extends Mailable
{
    use Queueable, SerializesModels;

    public $fromAddress;
    public $messageSubject;
    public $elements;
    public $images;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        /*
        PARAMS:
        from
        */

        $this->fromAddress = $data['fromAddress'];
        $this->messageSubject = $data['messageSubject'];
        $this->elements = $data['elements'];
        if(is_null($this->elements)){
            $this->elements = array();
        }
        $this->images = $data['images'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->fromAddress)->subject($this->messageSubject)->view('advertiseemail')->text('advertiseemail_plain_text');
    }
}
