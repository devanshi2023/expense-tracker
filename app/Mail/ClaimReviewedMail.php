<?php

namespace App\Mail;

use App\Models\ExpenseClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaimReviewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ExpenseClaim $claim)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Expense claim '.ucfirst($this->claim->status)
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.claim-reviewed'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
