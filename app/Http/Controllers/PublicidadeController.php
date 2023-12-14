<?php

namespace App\Http\Controllers;

use App\Models\Publicidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class PublicidadeController extends Controller
{
    /**
     * Visualizado del listado de la tabla
     * @return array json
     */
    public function index()
    {
        $publicidad = Publicidade::all();
        $data=[];
        foreach ($publicidad as $p) {
            $data[]=['id'=>$p->id,
            'titulo'=>$p->titulo,
            'importe'=>$p->importe,
            'nombre'=>$p->gestor->nombre,
            'apellido1'=>$p->gestor->apellido1,
            'apellido2'=>$p->gestor->apellido2,
            'nif'=>$p->gestor->nif];
        }
        return response()->json($data,201);
    }

    /**
     * Busca anuncios por aproximación del título
     * @param Request $request
     * @param Publicidade $publi
     * @return array json
    */
    public function buscaPubli(Request $request){
        $publicidad=new Publicidade();
        $data=$publicidad->buscar($request);
        return response()->json($data,200);
    }

    /**
     * Sube una foto de un cliente a la carpeta ./public/publicidad
     * @param Request $request
     * @return string
     */
    public function fotoPublicidad(Request $request){
        if ($request->hasFile('file')){
            $file=$request->file('file');
            $fileName=$file->getClientOriginalName();
            $filename=pathinfo($fileName, PATHINFO_FILENAME);
            $name_file=str_replace(' ', '_', $filename);
            $extension=$file->getClientOriginalExtension();
            $newFileName=date('His').'-'.$name_file.'.'.$extension;
            $file->move(public_path('publicidad/'), $newFileName);
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
            'imagen'=>'string|nullable',
            'titulo'=>'string|required',
            'importe'=>'numeric|required',
            'gestor_id'=>'numeric|required',
            'empresa_id'=>'numeric|required'
        ]);
            return $validator;
    }

    /**
     * Alta de un registro en la tabla
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
            $publicidad=new Publicidade();
            $publicidad->titulo=$request->titulo;
            $publicidad->importe=$request->importe;
            $publicidad->gestor_id=$request->gestor_id;
            $publicidad->empresa_id=$request->empresa_id;
            $publicidad->imagen=$request->imagen;
            $publicidad->save();
            $data=[
                'mensaje'=>"Mensaje publicitario creado perfectamente",
                'status'=>true,
                'Publicidad'=>$publicidad
            ];
            return  response()->json($data,201);
        }
    }

    /**
     * Visualización de un registro específico
     * @param Publicidade $publicidad
     * @return array json
     */
    public function show(Publicidade $publicidad)
    {
        $data=[
            'id'=>$publicidad->id,
            'imagen'=>$publicidad->imagen,
            'titulo'=>$publicidad->titulo,
            'importe'=>$publicidad->importe,
            'gestor'=>$publicidad->gestor,
            'empresa'=>$publicidad->empresa
        ];
        return response()->json($publicidad);
    }

    /**
     * Actualización de un registro específico en la tabla.
     * @param Request $request
     * @param Publicidade $publicidad
     * @return array json
     */
    public function update(Request $request, Publicidade $publicidad)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $publicidad->titulo=$request->titulo;
            $publicidad->importe=$request->importe;
            $publicidad->gestor_id=$request->gestor_id;
            $publicidad->empresa_id=$request->empresa_id;
            $publicidad->imagen=$request->imagen;
            $publicidad->save();
            $data=[
                'mensaje'=>'Anuncio publicitario actualizado correctamente',
                'status'=>true,
                'Publicidad'=>$publicidad
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Eliminación de un registro específico de la tabla.
     * @param Publicidade $publicidad
     * @return array json
     */
    public function destroy(Publicidade $publicidad)
    {
        try{
            $publicidad->delete();
            $data=[
                'mensaje'=>'Mensaje publicitario eliminado correctamente',
                'status'=>true,
                'publicidad'=>$publicidad
            ];
            return response()->json($data,201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible eliminar el anuncio publicitario, tiene datos asociados',
                'status'=>false,
                'publicidad'=>$e->getMessage()
            ];
            return response()->json($data,201);
        }
    }
}
