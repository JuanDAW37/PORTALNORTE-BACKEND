<?php

namespace App\Http\Controllers;

use App\Models\Actividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ActividadesController extends Controller
{
    /**
     * Listado de todas las actividades
     * @return array json
    */
    public function index()
    {
        $actividades = Actividade::all();
        $data=[];
        foreach ($actividades as $v){
            $data[]=[
                'id'=>$v->id,
                'actividad'=>$v->actividad,
                'tarifa'=>$v->tarifa,
                'descripcion'=>$v->descripcion,
                'personas'=>$v->personas,
                'duracion'=>$v->duracion,
                'tipo'=>$v->tiposactividade->tipo,
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Filtrar actividades por tarifa menor o mayor o igual, y/o personas y/o duración
     * @param Request $request
     * @param Actividade $actividad
     * @return array json
     */
    public function filtraActividad(Request $request, Actividade $actividad)
    {
        $data=[];
        $p=new Actividade();
        if ($request->tarifa) {
            $data=$p->consultar($request->tarifa, $request->personas, $request->duracion);
        }
        return response()->json($data,200);
    }

    /**
     * Sube una foto de una actividad a la carpeta ./public/actividades
     * @param Request $request
     * @return string
     */
    public function fotoActividad(Request $request){
        if ($request->hasFile('file')){
            $file=$request->file('file');
            $fileName=$file->getClientOriginalName();
            $filename=pathinfo($fileName, PATHINFO_FILENAME);
            $name_file=str_replace(' ', '_', $filename);
            $extension=$file->getClientOriginalExtension();
            $newFileName=date('His').'-'.$name_file.'.'.$extension;
            $file->move(public_path('actividades/'), $newFileName);
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
            'duracion'=>'numeric|nullable',
            'hora_inicio'=>'string|nullable',
            'hora_fin'=>'string|nullable',
            'personas'=>'numeric|nullable',
            'actividad'=>'string|required',
            'descripcion'=>'string|nullable',
            'tarifa'=>'required|numeric',
            'foto'=>'string|nullable',
            'tiposactividade_id'=>'numeric|required',
            'gestor_id'=>'numeric|required',
            'iva_id'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Almacenamiento de la actividad
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
            $actividad = new Actividade();
            $actividad->actividad=$request->actividad;
            $actividad->hora_inicio=$request->hora_inicio;
            $actividad->hora_fin=$request->hora_fin;
            $actividad->foto=$request->foto;
            $actividad->tiposactividade_id=$request->tiposactividade_id;
            $actividad->tarifa=$request->tarifa;
            $actividad->gestor_id=$request->gestor_id;
            $actividad->descripcion=$request->descripcion;
            $actividad->personas=$request->personas;
            $actividad->iva_id=$request->iva_id;
            $actividad->duracion=$request->duracion;
            $actividad->save();
            $data = [
                'mensaje' => "Actividad guardada correctamente",
                'status'=>true,
                'id' => $actividad->id
            ];
            return response()->json($data,200);
        }
    }

    /**
     * Recupero la información de la actividad solicitada
     * @param Actividade $actividad
     * @return array json
    */
    public function show(Actividade $actividad)
    {
        $data = [
            'id'=>$actividad->id,
            'actividad' => $actividad->actividad,
            'duracion' => $actividad->duracion,
            'personas' => $actividad->personas,
            'tarifa'=>$actividad->tarifa,
            'hora_inicio'=>$actividad->hora_inicio,
            'hora_fin'=>$actividad->hora_fin,
            'descripcion'=>$actividad->descripcion,
            'foto'=>$actividad->foto,
            'ubicacion' => $actividad->ubicaciones,
            'material' => $actividad->materiales,
            'guias' => $actividad->trabajadores,
            'gestor_id' => $actividad->gestor->id,
            'iva_id' => $actividad->iva->id,
            'tipo_iva'=>$actividad->iva->tipo,
            'tiposactividade_id' => $actividad->tiposactividade->id,
            'reservas' => $actividad->reservas
        ];
        return response()->json($data,201);

    }

    /**
     * Actualización de los datos de una actividad
     * @param Request $request
     * @param Actividade $actividad
     * @return array json
    */
    public function update(Request $request, Actividade $actividad)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            /**Actualización **/
            $actividad->actividad=$request->actividad;
            $actividad->hora_inicio=$request->hora_inicio;
            $actividad->hora_fin=$request->hora_fin;
            $actividad->foto=$request->foto;
            $actividad->tiposactividade_id=$request->tiposactividade_id;
            $actividad->tarifa=$request->tarifa;
            $actividad->gestor_id=$request->gestor_id;
            $actividad->descripcion=$request->descripcion;
            $actividad->personas=$request->personas;
            $actividad->iva_id=$request->iva_id;
            $actividad->duracion=$request->duracion;
            $actividad->save();
            $data = [
                'mensaje' => "Actividad guardada correctamente",
                'status'=>true,
                'id' => $actividad->id
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Borrado de la actividad
     * @param Actividade $actividad
     * @return array json
    */
    public function destroy(Actividade $actividad)
    {
        try{
            $actividad->delete();
            $data = [
                'mensaje' => 'Actividad eliminada correctamente',
                'status'=>true,
                'actividad' => $actividad
            ];
            return response()->json($data,201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar la actividad, hay datos asociados',
                'status'=>false,
                'actividad' => $e->getMessage()
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Crear relación con ubicaciones a través de la tabla pivote
     * @param Request $request
     * @return array json
    */
    public function attachUbicacion(Request $request)
    {
        $actividad = Actividade::find($request->actividade_id);
        $actividad->ubicaciones()->attach($request->ubicacione_id);
        $data = [
            'mensaje' => 'Ubicacion añadida correctamente',
            'activ' => $actividad->id,
            'ubicacion'=>$actividad->ubicaciones
        ];
        return response()->json($data,201);
    }

    /**
     * Quitar relación con ubicaciones
     * @param Request $request
     * @return array json
     */
    public function detachUbicacion(Request $request)
    {
        $actividad = Actividade::find($request->actividade_id);
        $actividad->ubicaciones()->detach($request->ubicacione_id);
        $data = [
            'mensaje' => 'Ubicacion quitada correctamente',
            'activ' => $actividad->id,
            'ubicacion'=>$actividad->ubicaciones
        ];
        return response()->json($data,201);

    }

    /**
     * Crear relación con materiales a través de la tabla pivote
     * @param Request $request
     * @return array json
     * */
    public function attachMaterial(Request $request)
    {
        $actividad = Actividade::find($request->actividade_id);

        $actividad->materiales()->attach($request->materiale_id);
        $data = [
            'mensaje' => 'Material añadido correctamente',
            'activ' => $actividad,
            'material'=>$actividad->materiales
        ];
        return response()->json($data,201);
    }

    /**
     * Quitar relación con materiales
     * @param Request $request
     * @return array json
     * */
    public function detachMaterial(Request $request)
    {
        $actividad = Actividade::find($request->actividade_id);
        $actividad->materiales()->detach($request->materiale_id);
        $data = [
            'mensaje' => 'Material quitado correctamente',
            'activ' => $actividad
        ];
        return response()->json($data,201);
    }

    /**
     * Crear relación con guías a través de la tabla pivote
     * @param Request $request
     * @return array json
     * */
    public function attachGuia(Request $request)
    {
        $actividad = Actividade::find($request->actividade_id);
        $actividad->trabajadores()->attach($request->trabajadore_id);
        $data = [
            'mensaje' => 'Guía unido correctamente',
            'activ' => $actividad,
            'guia'=>$actividad->trabajadores
        ];
        return response()->json($data,201);
    }

    /**
     * Quitar relación con guías
     * @param Request $request
     * @return array json
    */
    public function detachGuia(Request $request)
    {
        $actividad = Actividade::find($request->actividade_id);
        $actividad->trabajadores()->detach($request->trabajadore_id);
        $data = [
            'mensaje' => 'Guía quitado correctamente',
            'activ' => $actividad
        ];
        return response()->json($data,201);
    }
}
