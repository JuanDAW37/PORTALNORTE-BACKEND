<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Classes\Methods;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ReservasController extends Controller
{
    /**
     * Listado de reservas
     * @return array json
    */
    public function index()
    {
        $reserva = Reserva::all();
        $data=[];
        foreach ($reserva as $valor) {
            $data[]=[
            'reservas'=>$valor,
            'id'=>$valor->id,
            'numero'=>$valor->numero,
            'facturada'=>$valor->facturada,
            'fecha'=>$valor->fecha,
            'hora'=>$valor->hora,
            'personas'=>$valor->personas,
            'actividad'=>$valor->actividade->actividad,
            'trabajadores'=>$valor->actividade->trabajadores,
            'materiales'=>$valor->actividade->materiales,
            'ubicaciones'=>$valor->actividade->ubicaciones,
            'nombre'=>$valor->cliente->nombre,
            'nif'=>$valor->cliente->nif,
            'apellido1'=>$valor->cliente->apellido1,
            'apellido2'=>$valor->cliente->apellido2,
            'factura'=>$valor->factura
            ];
        };
        return response()->json($data,201);
    }

    /**
     * Filtrado de reservas aproximando por su número
     * @param Request $request
     * @param Reserva $reserva
     * @return array json
     */
    public function filtraReserv(Request $request)
    {
        $reserva=new Reserva();
        $data=$reserva->buscar($request);
        return response()->json($data, 200);
    }

    public function validarReserva(Request $request){
        $data=Reserva::getReservas($request);
        return response()->json($data);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'numero'=>'string|required',
            'facturada'=>'boolean|nullable',
            'fecha'=>'string|required',
            'hora'=>'string|required',
            'personas'=>'numeric|required',
            'actividade_id'=>'numeric|required',
            'cliente_id'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Se guarda la reserva y se envía un correo con el justificante de la reserva
     * @param Request $request
     * @return array json
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
            try {
                $timestamp = strtotime($request->fecha);
                $fecha = date("Y-m-d", $timestamp);
                $reserva = new Reserva();
                $reserva->numero = $request->numero;
                $reserva->fecha = $fecha;
                $reserva->hora = $request->hora;
                $reserva->personas = $request->personas;
                $reserva->actividade_id = $request->actividade_id;
                $reserva->cliente_id = $request->cliente_id;
                $reserva->save();
                /*Llamada a la función para realizar el envío del correo*/
                $enviado=Methods::reservaEmail($reserva);
                $data = [
                    'mensaje' => "Reserva guardada correctamente",
                    'status' => true,
                    'reserva' => $reserva,
                    'enviado'=>$enviado
                ];
                return response()->json($data,201);
            }catch(QueryException $e){
                $data=[
                    'mensaje'=>"No se pudo guardar la reserva",
                    'error'=>$e,
                    'status'=>false
                ];
                return response()->json($data,400);
            }
        }
    }

    /**
     * Consulta de la reserva
     * @param Reserva $reserva
     * @return array json
     */
    public function show(Reserva $reserva)
    {
        $data=[
            'numero'=>$reserva->numero,
            'facturada'=>$reserva->facturada,
            'fecha'=>$reserva->fecha,
            'hora'=>$reserva->hora,
            'personas'=>$reserva->personas,
            'actividad'=>$reserva->actividade,
            'trabajador'=>$reserva->actividade->trabajadores,
            'materiales'=>$reserva->actividade->materiales,
            'ubicaciones'=>$reserva->actividade->ubicaciones,
            'cliente'=>$reserva->cliente,
            'factura'=>$reserva->factura
        ];
        return response()->json($data);
    }

    /**
     * Actualización de la reserva
     * @param Request $request
     * @param Reserva $reserva
     * @return array json
     */
    public function update(Request $request, Reserva $reserva)
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
            $reserva->numero = $request->numero;
            $reserva->facturada=$request->facturada;
            $reserva->fecha = $fecha;
            $reserva->hora = $request->hora;
            $reserva->personas = $request->personas;
            $reserva->actividade_id = $request->actividade_id;
            $reserva->cliente_id = $request->cliente_id;
            $reserva->save();
            /*Llamada a la función para realizar el envío del correo*/
            $enviado=Methods::reservaEmail($reserva);
            $data = [
                'mensaje' => "Reserva modificada correctamente",
                "status"=> true,
                'reserva' => $reserva,
                'enviado'=>$enviado
        ];
            return response()->json($data,201);
        }
    }

    /**
     * @param Reserva $reserva
     * @return array json
    */
    public function destroy(Reserva $reserva)
    {
        try{
            $reserva->delete();
            $data = [
                'mensaje' => 'Reserva elminada correctamente',
                'status'=> true,
                'reserva' => $reserva
            ];
            return response()->json($data,201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible eliminar la reserva, tiene datos asociados',
                'status'=> false,
                'reserva'=>$e->getMessage()
            ];
            return response()->json($data, 401);
        }
    }
}
