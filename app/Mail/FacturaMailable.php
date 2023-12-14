<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Factura;

class FacturaMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $fact, $cliente, $nif, $domicilio, $descripcion;
    /**
     * Create a new message instance.
     */
    public function __construct(public Factura $factura, $cliente, $nif, $domicilio, $descripcion)
    {
        $this->fact=$factura;
        $this->cliente = $cliente;
        $this->nif = $nif;
        $this->domicilio = $domicilio;
        $this->descripcion = $descripcion;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.Factura',
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
