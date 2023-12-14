<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUbicacione;
use Illuminate\Http\Request;
use App\Models\Ubicacione;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class UbicacionesController extends Controller
{
    /**
     * Listado de ubicaciones
     * @return array json
     */
    public function index()
    {
        $ubicacion = Ubicacione::all();
        $data = [];
        foreach ($ubicacion as $u) {
            $data[] =
                [
                    'id' => $u->id,
                    'nombre' => $u->nombre,
                    'lat' => $u->lat,
                    'lon' => $u->lon
                ];
        }
        return response()->json($data, 201);
    }

    /**
     * Filtra las ubicaciones aproximando por nombre
     * @param Request $request
     * @param Ubicacione $ubicacion
     * @return array json
     */
    public function buscaUbicacion(Request $request)
    {
        $ubicacion=new Ubicacione();
        $data=$ubicacion->buscar($request);
        return response()->json($data,201);
    }

/**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre'=>'string|required',
            'lat'=>'numeric|required',
            'lon'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Guarda una ubicación
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
            $ubicacion = new Ubicacione();
            $ubicacion->nombre = $request->nombre;
            $ubicacion->lat = $request->lat;
            $ubicacion->lon = $request->lon;
            $ubicacion->save();
            $data = [
                'mensaje' => "Ubicacion guardada correctamente",
                'status'=>true,
                'id' => $ubicacion->id
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Muestra una ubicación
     * @param Ubicacione $ubicacion
     * @return array json
     */
    public function show(Ubicacione $ubicacion)
    {
        $data = [
            'id' => $ubicacion->id,
            'nombre' => $ubicacion->nombre,
            'lat' => $ubicacion->lat,
            'lon'=> $ubicacion->lon,
            'actividades' => $ubicacion->actividades
        ];
        return response()->json($data,201);
    }

    /**
     * Actualiza una ubicación
     * @param Request $request
     * @return array json
     */
    public function update(Request $request, Ubicacione $ubicacion)
    {
        $ubicacion->nombre = $request->nombre;
        $ubicacion->nombre = $request->nombre;
        $ubicacion->lat = $request->lat;
        $ubicacion->lon = $request->lon;
        $ubicacion->save();
        $data = [
            'mensaje' => "Ubicacion modificada correctamente",
            'ubicacion' => $ubicacion
        ];
        return response()->json($data, 200);
    }

    /**
     * Elimina una ubicación
     * @param Ubicacione $ubicacion
     * @return array json
     */
    public function destroy(Ubicacione $ubicacion)
    {
        try{
            $ubicacion->delete();
            $data = [
                'mensaje' => 'Ubicacion elminada correctamente',
                'status'=> true,
                'ubicacion' => $ubicacion
            ];
            return response()->json($data, 200);
        }catch(QueryException $e){
            $data = [
                'mensaje'=> 'Imposible borrar la ubicación, tiene datos asociados',
                'status'=> false,
                'ubicacion'=> $e->getMessage()
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Devuelve las actividades de la ubicación
     * @param Request $request
     * @return array json
     */
    public function actividades(Request $request)
    {
        $ubicacion = Ubicacione::find($request->ubicacion_id);
        $actividades = $ubicacion->actividades;
        $data = [
            'mensaje' => 'Actividades devueltas',
            'actividades' => $actividades
        ];
        return response()->json($data);
    }
}
