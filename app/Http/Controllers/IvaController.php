<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iva;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class IvaController extends Controller
{

    /**
     * Listado de IVA
     * @return array json
     */
    public function index()
    {
        $iva = Iva::all();
        return response()->json($iva,201);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre'=>'string|required',
            'tipo'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Guarda el IVA
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
                'errors'=>$validator->messages()
            ],201);
        }else{
            $iva = new Iva();
            $iva->nombre = $request->nombre;
            $iva->tipo = $request->tipo;
            $iva->save();
            $data = [
                'mensaje' => "Tipo de IVA guardado correctamente",
                'status'=>true,
                'iva' => $iva
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Enseña los datos de IVA
     * @param Iva $iva
     * @return array json
     */
    public function show(Iva $iva)
    {
        $data=[
            'iva'=>$iva,
            'actividad'=>$iva->actividades
        ];
        return response()->json($data,201);
    }

    /**
     * Actualiza el tipo de IVA
     * @param Request $request
     * @param Iva $iva
     * @return array json
     */
    public function update(Request $request, Iva $iva)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $iva->nombre = $request->nombre;
            $iva->tipo = $request->tipo;
            $iva->save();
            $data = [
                'mensaje' => "Tipo de IVA modificado correctamente",
                'status'=>true,
                'iva' => $iva
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Elimina el IVA
     * @param Iva $iva
     * @return array json
     */
    public function destroy(Iva $iva)
    {
        try{
            $iva->delete();
            $data = [
                'mensaje' => 'Tipo de IVA elminado correctamente',
                'status'=>true,
                'iva' => $iva
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible borrar el IVA, tiene datos asociados',
                'status'=>false,
                'iva'=>$e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
