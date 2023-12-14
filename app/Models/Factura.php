<?php

namespace App\Models;

use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Mail\FacturaMailable;
use Illuminate\Support\Facades\Mail;
use App\Models\Email;

class Factura extends Model
{
    use HasFactory;

    //Relación inversa 1 a 1 (1 Factura proviene de 1 reserva)
    public function reserva():BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    /**Relación inversa muchos a 1 (varias Facturas pertenecen a 1 cliente)*/
    public function cliente():BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Método para buscar una factura por número, fecha o aproximando por su concepto
     * @param Request $datos
     * @return $data
     */
    public function buscaFactura(Request $datos)
    {
        $campos=[
            'facturas.id as id',
            'facturas.numero as numero',
            'facturas.fecha as fecha',
            'clientes.nombre as nombre',
            'clientes.apellido1 as apellido1',
            'clientes.apellido2 as apellido2',
            'clientes.nif as nif',
            'facturas.concepto',
            'facturas.base',
            'facturas.iva',
            'facturas.cuota',
            'facturas.total'
        ];
        $fecha="";
        $data=[];
        if ($datos->numero) {
            $sp=DB::table('facturas')
                ->select($campos)
                ->leftJoin('clientes', 'clientes.id', '=', 'facturas.cliente_id')
                ->where('facturas.numero', '=', $datos->numero)->get();
        }
        if ($datos->fecha) {
            $timestamp = strtotime($datos->fecha);
            $fecha = date("Y-m-d", $timestamp);
            $sp=DB::table('facturas')
                ->select($campos)
                ->leftJoin('clientes', 'clientes.id', '=', 'facturas.cliente_id')
                ->where('facturas.fecha', '=', $fecha)->get();
        }
        if ($datos->concepto) {
            $sp=DB::table('facturas')
                ->select($campos)
                ->leftJoin('clientes', 'clientes.id', '=', 'facturas.cliente_id')
                ->where('facturas.concepto', 'like', '%' . $datos->concepto . '%')->get();
        }
        if($sp->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "facturas"=>$sp,
                "fecha"=>$fecha,
                "registros"=>$sp->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "facturas"=>'',
                'registros'=>0
            ];
        }
        return $data;
    }

    /**
     * Busca la última factura del año para devolver su número, en caso de no haber, devuelve 0
     * @param Request $request
     * @return $data
     */
    public static function buscaNum(Request $request){
        $data=[];
        $ps=DB::table('facturas')->select('numero')->whereYear('facturas.fecha', $request->anio)->orderBy('numero', 'desc')->limit(1);
        if($ps->count()>0){
            $numero=$ps->get();
            $data=[
                'mensaje'=>'hay datos',
                'status'=>true,
                "facturas"=>$numero,
            ];
        }else{
            $data=[
                'mensaje'=>'no hay datos',
                'status'=>false,
                "facturas"=>0,
            ];
        }
        return $data;
    }

    public static function imprimir(Request $request){
        $factu=Factura::where('id','=', $request->id)->get();
        $cliente = $factu[0]->cliente->nombre . ' ' . $factu[0]->cliente->apellido1 . ' ' . $factu[0]->cliente->apellido2;
        $nif=$factu[0]->cliente->nif;
        $domicilio = $factu[0]->cliente->direccione->calle . ' ' .
        $factu[0]->cliente->direccione->numero . ' ' .
        $factu[0]->cliente->direccione->km . ' ' .
        $factu[0]->cliente->direccione->bloque . ' ' .
        $factu[0]->cliente->direccione->piso . ' ' .
        $factu[0]->cliente->direccione->letra;
        $descripcion = $factu[0]->concepto;
        $factura=new Factura();
        $factura->base=$factu[0]->base;
        $factura->iva=$factu[0]->iva;
        $factura->cuota=$factu[0]->cuota;
        $factura->total=$factu[0]->total;
        $factura->numero=$factu[0]->numero;
        $timestamp = strtotime($factu[0]->fecha);
        $fecha = date("d-m-Y", $timestamp);
        $factura->fecha=$fecha;
        $pdf=PDF::loadView('report.Factura', compact('factura', 'cliente', 'nif', 'domicilio', 'descripcion'));
        return $pdf->stream();
    }

    public function email(Request $request){
        $factura=$this->cogerDatos($request);
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
     * Hace una consulta con el id enviado para devolver un objeto factura
     * @param Request $request
     * @return Factura $factura
     */
    public function cogerDatos(Request $request):Factura{
        $factu=Factura::where('id','=', $request->id)->get();
        $cliente = $factu[0]->cliente->nombre . ' ' . $factu[0]->cliente->apellido1 . ' ' . $factu[0]->cliente->apellido2;
        $nif=$factu[0]->cliente->nif;
        $domicilio = $factu[0]->cliente->direccione->calle . ' ' .
        $factu[0]->cliente->direccione->numero . ' ' .
        $factu[0]->cliente->direccione->km . ' ' .
        $factu[0]->cliente->direccione->bloque . ' ' .
        $factu[0]->cliente->direccione->piso . ' ' .
        $factu[0]->cliente->direccione->letra;
        $descripcion = $factu[0]->concepto;
        $factura=new Factura();
        $factura->base=$factu[0]->base;
        $factura->concepto=$descripcion;
        $factura->iva=$factu[0]->iva;
        $factura->cuota=$factu[0]->cuota;
        $factura->total=$factu[0]->total;
        $factura->numero=$factu[0]->numero;
        $factura->cliente_id=$factu[0]->cliente_id;
        $timestamp = strtotime($factu[0]->fecha);
        $fecha = date("d-m-Y", $timestamp);
        $factura->fecha=$fecha;
        return $factura;
    }
}
