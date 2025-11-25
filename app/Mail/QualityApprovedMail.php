<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QualityApprovedMail extends Mailable
{
    use Queueable, SerializesModels;
        public $quality;
        public $pdfContent;
        public $pdfName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quality, $pdfContent, $pdfName)
    {
        $this->quality = $quality;
        $this->pdfContent = $pdfContent;
        $this->pdfName = $pdfName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Quality Report: ' . $this->quality->dr_rr)
                    ->view('emails.quality_approved')
                    ->attachData($this->pdfContent, $this->pdfName, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
