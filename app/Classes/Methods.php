<?php
namespace App\Classes;

use App\Mail\ReservaMailable;
use App\Mail\FacturaMailable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Email;


class Methods
{
    /**
     * Método para enviar una reserva por email
     * @param $reserva
     * @return string
     */
    public static function reservaEmail($reserva): string
    {
        $email = Email::whereNotNull('email')->where('cliente_id', $reserva->cliente_id)->first();
        if (isset($email)) {
            $cliente = $reserva->cliente->nombre . ' ' . $reserva->cliente->apellido1 . ' ' . $reserva->cliente->apellido2;
            $nif = $reserva->cliente->nif;
            $domicilio = $reserva->cliente->direccione->calle . ' ' . $reserva->cliente->direccione->numero . ' ' .
                $reserva->cliente->direccione->km . ' ' . $reserva->cliente->direccione->bloque . ' ' . $reserva->cliente->direccione->piso . ' ' .
                $reserva->cliente->direccione->letra;
            $descripcion = $reserva->actividade->descripcion;
            Mail::to($email->get())->send(new ReservaMailable($reserva, $cliente, $nif, $domicilio, $descripcion));
            return "Email enviado";
        } else {
            return "Email no enviado, el cliente no dispone del mismo";
        }
    }

    /**
     * Método estático para enviar una factura por email
     * @param $factura
     * @return string
     */
    public static function facturaEmail($factura): string
    {
        $email = Email::whereNotNull('email')->where('cliente_id', $factura->cliente_id)->first();
        if (isset($email)) {
            $cliente = $factura->cliente->nombre . ' ' . $factura->cliente->apellido1 . ' ' . $factura->cliente->apellido2;
            $nif = $factura->cliente->nif;
            $domicilio = $factura->cliente->direccione->calle . ' ' .
                $factura->cliente->direccione->numero . ' ' .
                $factura->cliente->direccione->km . ' ' .
                $factura->cliente->direccione->bloque . ' ' .
                $factura->cliente->direccione->piso . ' ' .
                $factura->cliente->direccione->letra;
            $descripcion = $factura->concepto;
            Mail::to($email)->send(new FacturaMailable($factura, $cliente, $nif, $domicilio, $descripcion));
            return "Email enviado";
        } else {
            return "Email no enviado, el cliente no dispone del mismo";
        }
    }

    /**
     * Método estático para imprimir una factura
     * @param $factura
     * @return void
     */
    public static function facturaPDF($factura){
        $cliente = $factura->cliente->nombre . ' ' . $factura->cliente->apellido1 . ' ' . $factura->cliente->apellido2;
            $nif = $factura->cliente->nif;
            $domicilio = $factura->cliente->direccione->calle . ' ' .
                $factura->cliente->direccione->numero . ' ' .
                $factura->cliente->direccione->km . ' ' .
                $factura->cliente->direccione->bloque . ' ' .
                $factura->cliente->direccione->piso . ' ' .
                $factura->cliente->direccione->letra;
            $descripcion = $factura->concepto;
            $pdf=PDF::loadView('report.Factura', compact('factura', 'cliente', 'nif', 'domicilio', 'descripcion'));
            $pdf->download('factura.pdf');
    }
}
?>
