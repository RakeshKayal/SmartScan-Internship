<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $company,
        public array $customer,
        public array $invoice,
        public array $items,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Invoice #' . $this->invoice['number'] . ' from ' . $this->company['name'],
        );
    }

   public function content(): Content
{
    return new Content(
        view: 'emails.invoice',
        with: [
            'company'  => $this->company,
            'customer' => $this->customer,
            'invoice'  => $this->invoice,
            'items'    => $this->items,
        ],
    );
}
}