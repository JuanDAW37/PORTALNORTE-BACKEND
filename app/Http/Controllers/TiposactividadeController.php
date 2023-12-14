<?php

namespace App\Http\Controllers;

use App\Models\Tiposactividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class TiposactividadeController extends Controller
{
    /**
     * Listado de tipos de actividades
     * @return array json
     */
    public function index()
    {
        $data=[];
        $tipos = Tiposactividade::all();
        foreach ($tipos as $tipo){
            $data[]=[
                "id"=>$tipo->id,
                "tipo"=>$tipo->tipo,
                "actividad"=>$tipo->actividades
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Filtra los tipos de actividad
     * @param Request $request
     * @param Tiposactividade $tipo
     * @return array json
     */
    public function filtraTipo(Request $request)
    {
        $tipo=new Tiposactividade();
        $data=$tipo->filtrar($request);
        return response()->json($data,200);
    }

    /**
     * Sube una foto de un cliente a la carpeta ./public/tipos
     * @param Request $request
     * @return string
     */
    public function fotoTipo(Request $request){
        if ($request->hasFile('file')){
            $file=$request->file('file');
            $fileName=$file->getClientOriginalName();
            $filename=pathinfo($fileName, PATHINFO_FILENAME);
            $name_file=str_replace(' ', '_', $filename);
            $extension=$file->getClientOriginalExtension();
            $newFileName=date('His').'-'.$name_file.'.'.$extension;
            $file->move(public_path('tipos/'), $newFileName);
            $data=[
                'mensaje'=>'Imagen cargada',
                'status'=>true,
                'request'=>$request
            ];
            return response()->json($data, 200);
        }else{
            $data=[
                'mensaje'=>'Imagen no cargada',
                'status'=>false,
                'request'=>$request
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
public function validar(Request $request){
    $validator = Validator::make($request->all(), [
        'tipo'=>'string|required',
        'foto'=>'string|nullable',
        'icono'=>'string|nullable'
    ]);
    return $validator;
}

    /**
     * Almacena un tipo de actividad
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
            $tipo = new Tiposactividade();
            $tipo->tipo = $request->tipo;
            $tipo->foto = $request->foto;
            $tipo->icono = $request->icono;
            $tipo->save();
            $data = [
                'mensaje' => "Tipo de actividad creado perfectamente",
                'status' => true,
                'id' => $tipo->id
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Muestra un tipo de actividad
     * @param Tiposactividade $tipo
     * @return array json
     */
    public function show(Tiposactividade $tipo)
    {
        $data=[
            'id'=>$tipo->id,
            'tipo'=>$tipo->tipo,
            'foto'=>$tipo->foto,
            'icono'=>$tipo->icono,
            'actividades'=>$tipo->actividades
        ];
        return response()->json($data,201);
    }

    /**
     *
     * Actualiza el tipo de actividad
     * @param Request $request
     * @param Tiposactividade $tipo
     * @return array json
     */
    public function update(Request $request, Tiposactividade $tipo)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $tipo->tipo = $request->tipo;
            $tipo->foto = $request->foto;
            $tipo->icono = $request->icono;
            $tipo->save();
            $data = [
                'mensaje' => "Tipo de actividad actualizado perfectamente",
                'status' => true,
                'tipo' => $tipo
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Elimina el tipo de actividad
     * @param Tiposactividade $tipo
     * @return array json
     */
    public function destroy(Tiposactividade $tipo)
    {
        try{
            $tipo->delete();
            $data = [
                'mensaje' => "Tipo de actividad eliminado perfectamente",
                "status"=> true,
                'tipo' => $tipo
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible eliminar el tipo de actividad, tiene datos asociados',
                'status'=>false,
                'tipo'=>$e->getMessage()
            ];
            return response()->json($data);
        }
    }

    /**
     * Busca el tipo de actividad por su id
     * @param Request $info
     * @param Tiposactividade $tipo
     * @return array json
     */
    public function consulta(Request $info, Tiposactividade $tipo)
    {
        $actividades = Tiposactividade::find($tipo->id);
        $data = [
            'mensaje' => 'Nombre de la actividad',
            'nombre' => $actividades->tipo
        ];
        return response()->json($data, 201);
    }
}
