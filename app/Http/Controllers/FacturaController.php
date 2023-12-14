<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Factura;
use App\Classes\Methods;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{
    /**
     * Devuelve el listado de facturas
     * @return array json
     */
    public function index()
    {
        $data=[];
        $facturas = Factura::all();
        foreach ($facturas as $factura){
            $data[]=[
                'id'=>$factura->id,
                'numero'=>$factura->numero,
                'fecha'=>$factura->fecha,
                'nombre'=>$factura->cliente->nombre,
                'apellido1'=>$factura->cliente->apellido1,
                'apellido2'=>$factura->cliente->apellido2,
                'nif'=>$factura->cliente->nif,
                'concepto'=>$factura->concepto,
                'base'=>$factura->base,
                'iva'=>$factura->iva,
                'cuota'=>$factura->cuota,
                'total'=>$factura->total,
                'reserva'=>$factura->cliente,
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Filtra el listado de facturas por fecha y/o número y/o concepto aproximando por éste último
     * @param Request $request
     * @return array json
    */
    public function filtraFact(Request $request){
        $factu=new Factura();
        $data=$factu->buscaFactura($request);
        return response()->json($data,200);
    }

    /**
     * Método para localizar la última factura del año, sirve para generar una nueva desde una Reserva
     * @param Request $request
     * @return array json
     */
    public function cogeNumFactura(Request $request){
        $data=Factura::buscaNum($request);
        return response()->json($data,200);
    }

    /**
     * Método para imprimir una factura
     * @param Request  $request
     * @return string
    */
    public function verPDF(Request $request){
        $data=Factura::imprimir($request);
        return $data;
    }

    /**
     * Método para enviar la factura por email
     * @param Factura $factura
     * @return string
     */
    public function enviaEmail(Request $request){
        $factura=new Factura();
        $data=$factura->email($request);
        $data=[
            'mensaje'=>$data
        ];
        return response()->json($data);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'numero'=>'numeric|required',
            'fecha'=>'string|required',
            'cliente_id'=>'numeric|required',
            'reserva_id'=>'numeric|required',
            'concepto'=>'string|required',
            'base'=>'numeric|required',
            'iva'=>'numeric|required',
            'cuota'=>'numeric|required',
            'total'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Guarda una factura y la envía por email
     * @param Request $request
     * @param Factura $factura
     * @return array
    */
    public function store(Request $request)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages(),
                'enviado'=>'no enviado'
            ],201);
        }else{
                $factura = new Factura();
                $factura->numero = $request->numero;
                $factura->fecha = $request->fecha;
                $factura->cliente_id = $request->cliente_id;
                $factura->reserva_id = $request->reserva_id;
                $factura->concepto = $request->concepto;
                $factura->base = $request->base;
                $factura->iva = $request->iva;
                $factura->cuota = $request->cuota;
                $factura->total = $request->total;
                $factura->save();
                /*Llamada a la función para realizar el envío del correo*/
                $enviado=Methods::facturaEmail($factura);
                $data = [
                    'mensaje' => "Factura guardada correctamente",
                    'status'=>true,
                    'factura' => $factura,
                    'id'=>$factura->id,
                    'enviado'=>$enviado
                ];
                return response()->json($data,201);
        }
    }

    /**Muestra una factura
     * @param Factura $factura
     * @return array json
     */
    public function show(Factura $factura)
    {
        $data=[
            'factura'=>$factura,
            'reserva'=>$factura->reserva,
            'cliente'=>$factura->cliente,
        ];
        return response()->json($data,201);
    }

    /**Actualiza una factura y la envía por email
     * @param Request $request
     * @param Factura $factura
     * @return array json
    */
    public function update(Request $request, Factura $factura)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages(),
                'enviado'=>'no enviado'
            ],201);
        }else{
            $timestamp = strtotime($request->fecha);
            $fecha = date("Y-m-d", $timestamp);
            $factura->numero = $request->numero;
            $factura->fecha = $fecha;
            $factura->cliente_id = $request->cliente_id;
            $factura->reserva_id = $request->reserva_id;
            $factura->concepto = $request->concepto;
            $factura->base = $request->base;
            $factura->iva = $request->iva;
            $factura->cuota = $request->cuota;
            $factura->total = $request->total;
            $factura->save();
            /*Llamada a la función para realizar el envío del correo*/
            $enviado=Methods::facturaEmail($factura);
            $data = [
                'mensaje' => "Factura modificada correctamente",
                'status'=>true,
                'factura' => $factura,
                'enviado'=>$enviado
            ];
            return response()->json($data,201);
        }
    }
}
