<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemMessageNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $htmlBody,
        public ?string $companyName = null,
        public ?string $logoUrl = null
    ) {}

    public function build(): self
    {
        return $this->subject($this->subjectLine)
            ->view('emails.system-message-notification');
    }
}
