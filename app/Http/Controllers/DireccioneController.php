<?php

namespace App\Http\Controllers;

use App\Models\Direccione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class DireccioneController extends Controller
{
    /**
     * Listado de direcciones.
     * @return array json
     */
    public function index()
    {
        $data=[];
        $direccion = Direccione::all();
        foreach ($direccion as  $dir) {
            $data[]=[
                'id'=>$dir->id,
                'calle'=>$dir->calle,
                'numero'=>$dir->numero,
                'km'=>$dir->km,
                'bloque'=>$dir->bloque,
                'piso'=>$dir->piso,
                'departamento'=>$dir->letra,
                'cp'=>$dir->cp,
                'ciudad'=>$dir->cp->ciudade->ciudad,
                'provincia'=>$dir->cp->ciudade->provincia
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Busca una dirección por su calle, km, número, bloque, piso o letra
     * @param Request $request
     * @return array json
     */
    public function buscarDireccion(Request $request){
        $data=Direccione::buscaDireccion($request);
        return response()->json($data,201);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'calle'=>'string|required',
            'numero'=>'string|nullable',
            'km'=>'string|nullable',
            'bloque'=>'string|nullable',
            'piso'=>'string|nullable',
            'letra'=>'string|nullable',
            'cp_id'=>'numeric|required'
        ]);
        return $validator;
}


    /**
     * Guardar una dirección.
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
            $direccion = new Direccione();
            $direccion->calle = $request->calle;
            $direccion->numero = $request->numero;
            $direccion->km = $request->km;
            $direccion->bloque = $request->bloque;
            $direccion->piso = $request->piso;
            $direccion->letra = $request->letra;
            $direccion->cp_id=$request->cp_id;
            $direccion->save();
            $data = [
                'mensaje' => 'Dirección guardada correctamente',
                'status'=>true,
                'direccion' => $direccion,
                'id'=>$direccion->id
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Muestra la dirección.
     * @param Direccione $direccione
     * @return array json
     */
    public function show(Direccione $direccion)
    {
        $data=[
            'direccion'=>$direccion,
            'cp'=>$direccion->cp,
            'clientes'=>$direccion->clientes,
            'empresa'=>$direccion->empresa,
            'gestor'=>$direccion->gestor,
            'trabajadores'=>$direccion->trabajadores
        ];
        return response()->json($data);
    }


    /**
     * Actualiza la dirección.
     * @param Request $request
     * @param Direccione $direccione
     * @return array json
     */
    public function update(Request $request, Direccione $direccion)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $direccion->calle = $request->calle;
            $direccion->numero = $request->numero;
            $direccion->km = $request->km;
            $direccion->bloque = $request->bloque;
            $direccion->piso = $request->piso;
            $direccion->letra = $request->letra;
            $direccion->cp_id=$request->cp_id;
            $direccion->save();
            $data = [
                'mensaje' => 'Dirección actualizada correctamente',
                'status'=>true,
                'direccion' => $direccion
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Elimina la dirección.
     * @param Direccione $direccione
     * @return array json
     */
    public function destroy(Direccione $direccion)
    {
        try{
            $direccion->delete();
            $data = [
                'mensaje' => 'Dirección eliminada correctamente',
                'status'=>true,
                'direccion' => $direccion
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar la Dirección, tiene datos asociados',
                'status'=>false,
                'numero' => $e->getMessage()
            ];
            return response()->json($data, 201);
        }

    }
}
