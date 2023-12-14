<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class CpController extends Controller
{
    /**
     * Listado de Códigos postales
     * @return array json
     */
    public function index()
    {
        $data=[];
        $cp = Cp::all();
        foreach ($cp as $c){
            $data[]=[
                'id'=>$c->id,
                'numero'=>$c->numero,
                'direccion'=>$c->direcciones
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Buscar un código postal
     * @param Request $request
     * @param Cp $cp
     * @return array json
     */
    public function buscaCp(Request $request)
    {
        $c=new Cp();
        $data=$c->buscarCp($request);
        return response()->json($data,201);

    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'numero'=>'numeric|required',
            'ciudade_id'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Crear un nuevo código postal
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
            $cp = new Cp();
            $cp->numero = $request->numero;
            $cp->ciudade_id = $request->ciudade_id;
            $cp->save();
            $data = [
                'mensaje' => "Cp guardado correctamente",
                'status'=>true,
                'numero' => $cp,
                'id'=>$cp->id
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Mostrar un código postal
     * @param Cp $cp
     * @return array json
     */
    public function show(Cp $cp)
    {
        $data=[
            'codigo'=>$cp->id,
            'CP'=>$cp->numero,
            'ciudad'=>$cp->ciudade,
            'provincia'=>$cp->ciudade->provincia,
            'pais'=>$cp->ciudade->provincia->paise
        ];
        return response()->json($data);
    }

    /**
     * Actualizar un código postal
     * @param Request $request
     * @param Cp $cp
     * @return array json
     */
    public function update(Request $request, Cp $cp)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $cp->numero = $request->numero;
            $cp->ciudade_id = $request->ciudade_id;
            $cp->save();
            $data = [
                'mensaje' => "Cp actualizado correctamente",
                'status'=>true,
                'numero' => $cp
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Eliminar un código postal
     * @param Cp $cp
     * @return array json
     */
    public function destroy(Cp $cp)
    {
        try{
            $cp->delete();
            $data = [
                'mensaje' => 'CP eliminado correctamente',
                'status'=>true,
                'numero' => $cp
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar el Cp, tiene datos asociados',
                'status'=>false,
                'numero' => $e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
