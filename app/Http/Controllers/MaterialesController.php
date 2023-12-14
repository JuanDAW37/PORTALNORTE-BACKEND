<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materiale;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class MaterialesController extends Controller
{
    /**
     * Listado de materiales
     * @return array json
    */
    public function index()
    {
        $data=[];
        $materiales = Materiale::all();
        foreach ($materiales as $m){
            $data[]=[
                'id'=>$m->id,
                'nombre'=>$m->nombre,
                'actividades'=>$m->actividades
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Filtra el listado de materiales aproximando por su nombre
     * @param Request $request
     * @param Materiale $mat
     * @return array json
    */
    public function filtraMat(Request $request){
        $mat=new Materiale();
        $data=$mat->buscar($request);
        return response()->json($data,200);
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
     * Método que almacena el material
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
            $materiales = new Materiale();
            $materiales->nombre = $request->nombre;
            $materiales->save();
            $data = [
                'mensaje' => "Material guardado correctamente",
                'status'=>true,
                'id' => $materiales->id
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Muestra la información de un material
     * @param Materiale $materiales
     * @return array json
    */
    public function show(Materiale $material)
    {
        $data=[
            'id'=>$material->id,
            'nombre'=>$material->nombre,            
            'actividades'=>$material->actividades
        ];
        return response()->json($data,201);
    }

    /**
     * Actualiza los datos del material
     * @param Request $request
     * @param Materiale $materiales
     * @return array json
    */
    public function update(Request $request, Materiale $material)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $material->nombre = $request->nombre;
            $material->save();
            $data = [
                'mensaje' => "Material modificado correctamente",
                'status'=>true,
                'materiales' => $material
        ];
            return response()->json($data,201);
        }
    }

    /**
     * Borrado del material
     * @param Materiale $materiales
     * @return array json
    */
    public function destroy(Materiale $material){
        try{
            $material->delete();
            $data=[
                'mensaje'=>'Material elminado correctamente',
                'status'=>true,
                'materiales'=>$material
            ];
            return  response()->json($data,201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible borrar el material, tiene datos asociados',
                'status'=>false,
                'materiales'=>$e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
