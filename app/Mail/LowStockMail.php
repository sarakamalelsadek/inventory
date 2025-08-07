<?php

namespace App\Mail;

use App\Models\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    

    public $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function build()
    {
        return $this->subject('Alert of low stock!')
                    ->view('emails.low_stock');
    }
}
