<?php

namespace App\Http\Controllers;

use App\Models\Ciudade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class CiudadesController extends Controller
{
    /**
     * Listado de todas las ciudades
     * @return array json
     */
    public function index()
    {
        $ciudades = Ciudade::all();
        $data=[];
        foreach ($ciudades as $ciudad){
            $data[]=
            ['id'=>$ciudad->id,
            'nombre'=>$ciudad->ciudad,
            'provincia'=>$ciudad->provincia,
            'pais'=>$ciudad->provincia->paise
            ]
;        }
        return response()->json($data,201);
    }

    /**
     * Busca una ciudad
     * @param Request $request
     * @param Ciudade $ciudad
     * @return array json
     */
    public function buscaCiudad(Request $request, Ciudade $ciudad)
    {
        $c=new Ciudade();
        $data=$c->daCiudad($request);
        return response()->json($data,200);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'ciudad'=>'string|required',
            'provincia_id'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Crear una nueva ciudad
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
            $ciudades = new Ciudade();
            $ciudades->ciudad = $request->ciudad;
            $ciudades->provincia_id = $request->provincia_id;
            $ciudades->save();
            $data = [
                'mensaje' => "Ciudad guardada correctamente",
                'status'=>true,
                'ciudad' => $ciudades
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Muestra la información de una ciudad
     * @param Ciudade $ciudades
     *  @return array json
     */
    public function show(Ciudade $ciudad)
    {
        $data=[
            'ciudad'=>$ciudad->ciudad,
            'provincia'=>$ciudad->provincia->provincia,
            'pais'=>$ciudad->provincia->paise->nombre
        ];
        return response()->json($data,201);
    }

    /**
     * Actualiza una ciudad
     * @param Request $request
     * @param Ciudade $ciudades
     * @return array json
     */
    public function update(Request $request, Ciudade $ciudad)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $ciudad->ciudad = $request->ciudad;
            $ciudad->provincia_id = $request->provincia_id;
            $ciudad->save();
            $data = [
                'mensaje' => "Ciudad guardada correctamente",
                'status'=>true,
                'Ciudad' => $ciudad
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Eliminar una ciudad
     * @param Ciudade $ciudades
     *  @return array json
     */
    public function destroy(Ciudade $ciudad)
    {
        try{
            $ciudad->delete();
            $data = [
                'mensaje' => 'Ciudad eliminada correctamente',
                'status'=>true,
                'Ciudad' => $ciudad
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar la ciudad, tiene datos asociados',
                'status'=>false,
                'Ciudad' => $e->getMessage()
            ];
            return response()->json($data, 201);
        }

    }
}
