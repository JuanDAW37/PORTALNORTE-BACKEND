<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paise;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class PaisesController extends Controller
{
    /**
     * Listado de países
     * @return array json
     */
    public function index()
    {
        $pais = Paise::all();
        return response()->json($pais,201);
    }

    /**
     * Buscar un país
     * @param Request $request
     * @return array json
     */
    public function buscarPais(Request $request){
        $data=Paise::buscaPais($request);
        return response()->json($data, 200);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre'=>'string|required'
        ]);
        return $validator;
    }

    /**
     * Almacena el país
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
            $pais = new Paise();
            $pais->nombre = $request->nombre;
            $pais->save();
            $data = [
                'mensaje' => "País guardado correctamente",
                'status' => true,
                'pais' => $pais
            ];
            return response()->json($data);
        }
    }

    /**
     * Muestra el país
     * @param Paise $pais
     * @return array json
     */
    public function show(Paise $pais)
    {
        $data=[
            'mensaje'=>'País encontrado',
            'pais'=>$pais,
            'provincias'=>$pais->provincia
        ];
        return response()->json($data,201);
    }

    /**
     * Actualiza el país
     * @param Request $request
     * @param Paise $pais
     * @return array json
     */
    public function update(Request $request, Paise $pais)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $pais->nombre = $request->nombre;
            $pais->save();
            $data = [
                'mensaje' => "País modificado correctamente",
                'status' => true,
                'pais' => $pais
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Borra el país
     * @param Paise $pais
     * @return array json
     */
    public function destroy(Paise $pais)
    {
        try{
            $pais->delete();
            $data = [
                'mensaje' => 'País elminado correctamente',
                'status'=> true,
                'pais' => $pais
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'no se puede eliminar el país, tiene datos asociados',
                'status'=> false,
                'pais'=>$e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
