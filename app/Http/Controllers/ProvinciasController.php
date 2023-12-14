<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ProvinciasController extends Controller
{
    /**
     * Listado de las Provincias
     * @return array json
     */
    public function index()
    {
        $provincia = Provincia::all();
        return response()->json($provincia,201);
    }

    /**
     * Buscar una provincia
     * @param Request $request
     * @return array json
     */
    public function buscarProvincia(Request $request){
        $data=Provincia::buscaProvincia($request);
        return response()->json($data, 200);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'codigo'=>'numeric|required',
            'nombre'=>'string|required',
            'paise_id'=>'numeric|nullable'
        ]);
        return $validator;
    }

    /**
     * Guarda la provincia
     * @param Request provincia
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
            $provincia = new Provincia();
            $provincia->codigo = $request->codigo;
            $provincia->nombre = $request->nombre;
            $provincia->paise_id = $request->paise_id;
            $provincia->save();
            $data = [
                'mensaje' => "Provincia guardada correctamente",
                'status' => true,
                'provincia' => $provincia
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Muestra la provincia
     * @param Provincia $provincia
     * @return array json
    */
    public function show(Provincia $provincia)
    {
        $data=[
            'mensaje'=>'Provincia encontrada',
            'provincia'=>$provincia,
            'pais'=>$provincia->paise,
            'ciudades'=>$provincia->ciudades
        ];
        return response()->json($data,201);
    }

    /**
    * Actualiza la Provincia
    * @param Request $request
    * @param Provincia $provincia
    * @return array json
    */
    public function update(Request $request, Provincia $provincia)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $provincia->codigo = $request->codigo;
            $provincia->nombre = $request->nombre;
            $provincia->paise_id = $request->paise_id;
            $provincia->save();
            $data = [
                'mensaje' => "Provincia modificada correctamente",
                'status' => true,
                'provincia' => $provincia
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Elimina la provincia
     * @param Provincia $provincia
     * @return array json
     */
    public function destroy(Provincia $provincia)
    {
        try{
            $provincia->delete();
            $data = [
                'mensaje' => 'Provincia elminada correctamente',
                'status'=> true,
                'provincia' => $provincia
            ];
            return response()->json($data,201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible borrar la provincia, tiene datos asociados',
                'status'=> false,
                'provincia'=> $e->getMessage()
            ];
            return response()->json($data,201);
        }
    }
}
