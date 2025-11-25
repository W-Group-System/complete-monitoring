<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QualityBulkApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attachmentsList;
    public $emailSubject;

    public function __construct($attachmentsList, $emailSubject)
    {
        $this->attachmentsList = $attachmentsList;
        $this->emailSubject = $emailSubject;
    }

    public function build()
    {
        $email = $this->subject($this->emailSubject)
            ->view('emails.quality_bulk_approved');

        foreach ($this->attachmentsList as $file) {
            $email->attachData(
                $file['content'],
                $file['name'],
                ['mime' => 'application/pdf']
            );
        }

        return $email;
    }
}
