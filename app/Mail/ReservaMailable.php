<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;

class ReservaMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $reser, $cliente, $nif, $domicilio, $descripcion;


    /**
     * Create a new message instance.
     */
    public function __construct(public Reserva $reserva, $cliente, $nif, $domicilio, $descripcion)
    {
        $this->reser=$reserva;
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
            from: new Address('jymtr1968@gmail.com','Juan Tenreiro'),
            subject: 'Confirmación de Reserva',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.confirmarReserva',
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
