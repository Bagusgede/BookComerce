<?php

namespace App\Mail;

use App\Models\Book;
use App\Models\EbookDelivery;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EbookDownloadLink extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $book;
    public $downloadLink;
    public $delivery;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, Book $book, string $downloadLink, EbookDelivery $delivery)
    {
        $this->order = $order;
        $this->book = $book;
        $this->downloadLink = $downloadLink;
        $this->delivery = $delivery;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'File Ebook Anda Siap Diunduh - ' . $this->book->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ebook-download-link',
            with: [
                'order' => $this->order,
                'book' => $this->book,
                'downloadLink' => $this->downloadLink,
                'delivery' => $this->delivery,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
