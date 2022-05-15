<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    private string $template = 'otp-code';
    private string $subjectText = 'OTP code';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function setSubject(string $sub): static
    {
        $this->subjectText = $sub;
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $details = $this->details;
        return $this->subject($this->subjectText)
            ->view("emails.{$this->template}", compact('details'));
    }
}
