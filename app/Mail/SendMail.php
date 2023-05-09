<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    // global
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;

        // email type
        if($data['email_type'] == 'new-account') {
            return $this->markdown('template.email.new-account')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'reset-password') {
            return $this->markdown('template.email.reset-password')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'submit-round') {
            return $this->markdown('template.email.submit-round')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'revision-round') {
            return $this->markdown('template.email.revision-round')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'approve-round') {
            return $this->markdown('template.email.approve-round')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'approve2-round') {
            return $this->markdown('template.email.approve2-round')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'revision-clear-round') {
            return $this->markdown('template.email.revision-clear-round')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'submit-pekerjaan') {
            return $this->markdown('template.email.submit-pekerjaan')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        } 
        elseif ($data['email_type'] == 'revision-pekerjaan') {
            return $this->markdown('template.email.revision-pekerjaan')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        } 
        elseif ($data['email_type'] == 'approve-pekerjaan') {
            return $this->markdown('template.email.approve-pekerjaan')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        } 
        elseif ($data['email_type'] == 'approve2-pekerjaan') {
            return $this->markdown('template.email.approve2-pekerjaan')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        elseif($data['email_type'] == 'revision-clear-round') {
            return $this->markdown('template.email.revision-clear-round')
                        ->subject($data['title'])
                        ->with('data', $this->data);
        }
        
    }
}           