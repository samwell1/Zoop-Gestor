<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NFe extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	 protected $order;
	 
    public function __construct($order)
    {
		$this->order = $order;
    }
	
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->view('mail.nfe')
	   ->attachData($this->order['pdf'],'danfe.pdf')
	   ->attachData($this->order['xml'],'nfe.xml')
	   ->with(['order' => $this->order]);
    }
}
