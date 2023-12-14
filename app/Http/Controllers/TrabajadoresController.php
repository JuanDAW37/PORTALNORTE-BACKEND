<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajadore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class TrabajadoresController extends Controller
{
    /**
     * Listado de trabajadores
     * @return array json
    */
    public function index()
    {
        $data=[];
        $trabajador = Trabajadore::all();
        foreach ($trabajador as $t){
            $data[]=[
                'id'=>$t->id,
                'nombre'=>$t->nombre,
                'apellido1'=>$t->apellido1,
                'apellido2'=>$t->apellido2,
                'nif'=>$t->nif,
                'direccione_id'=>$t->direccione_id,
                'calle'=>$t->direccione->calle,
                'km'=>$t->direccione->km,
                'numero'=>$t->direccione->numero,
                'bloque'=>$t->direccione->bloque,
                'piso'=>$t->direccione->piso,
                'letra'=>$t->direccione->letra,
                'cp'=>$t->direccione->cp->numero,
                'ciudad'=>$t->direccione->cp->ciudade->ciudad,
                'provincia'=>$t->direccione->cp->ciudade->provincia,
                'telefonos'=>$t->telefonos,
                'emails'=>$t->emails,
                'actividades'=>$t->actividades,
                'empresa'=>$t->empresa
            ];
        }
        return response()->json($data,201);
    }

    /**
     * Filtra los trabajadores por nif y/o nombre y/o apellido1 y/o apellido2
     * @param Request $request
     * @param Trabajadore $trabajador
     * @return array json
    */
    public function filtraTrab(Request $request)
    {
        $trabajador=new Trabajadore();
        $data=$trabajador->buscar($request);
        return response()->json($data,200);
    }

    /**
     * Login para el trabajador
     * @param Request $request
     * @return array json
    */
    public function loginTrab(Request $request){
        $trabajador=new Trabajadore();
        $data=$trabajador->login($request);
        return response()->json($data,200);
    }

    /**
     * Verifica que no haya más clientes con el mismo NIF, al mismo tiempo que por el nombre de usuario
     */
    public function nifUserTrab(Request $request){
        $data=Trabajadore::nifUser($request);
        return response()->json($data, 201);
    }

    /**
     * Sube una foto de un cliente a la carpeta ./public/trabajadores
     * @param Request $request
     * @return string
     */
    public function fotoTrabajador(Request $request){
        if ($request->hasFile('file')){
            $file=$request->file('file');
            $fileName=$file->getClientOriginalName();
            $filename=pathinfo($fileName, PATHINFO_FILENAME);
            $name_file=str_replace(' ', '_', $filename);
            $extension=$file->getClientOriginalExtension();
            $newFileName=date('His').'-'.$name_file.'.'.$extension;
            $file->move(public_path('trabajadores/'), $newFileName);
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
            'nombre'=>'string|required',
            'apellido1'=>'string|required',
            'apellido2'=>'string|nullable',
            'nif'=>'string|required',
            'direccione_id'=>'numeric|required',
            'foto'=>'nullable',
            'user'=>'string|required',
            'password'=>'string|required',
            'contrato'=>'string|required',
            'sueldo'=>'numeric|required',
            'incentivo'=>'numeric|nullable',
            'empresa_id'=>'numeric|required',
            'remember_token'=>'string|nullable',
            'rol'=>'numeric|required'
        ]);
        return $validator;
    }

    /**
     * Guarda un trabajador
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
            $trabajador = new Trabajadore();
            $trabajador->nombre=$request->nombre;
            $trabajador->apellido1=$request->apellido1;
            $trabajador->apellido2=$request->apellido2;
            $trabajador->nif=$request->nif;
            $trabajador->direccione_id=$request->direccione_id;
            $trabajador->foto=$request->foto;
            $trabajador->user=$request->user;
            $trabajador->password=Hash::make($request->password);
            $trabajador->contrato=$request->contrato;
            $trabajador->sueldo=$request->sueldo;
            $trabajador->incentivo=$request->incentivo;
            $trabajador->empresa_id=$request->empresa_id;
            $trabajador->rol=$request->rol;
            $trabajador->remember_token=$request->remember_token;
            $trabajador->save();
            $data = [
                'mensaje' => "Trabajador guardado correctamente",
                'status'  => true,
                'id' => $trabajador->id
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Muestra un trabajador
     * @return array json
    */
    public function show(Trabajadore $trabajador)
    {
            $data=[
                'id'=>$trabajador->id,
                'nombre'=>$trabajador->nombre,
                'apellido1'=>$trabajador->apellido1,
                'apellido2'=>$trabajador->apellido2,
                'direccione_id'=>$trabajador->direccione_id,
                'nif'=>$trabajador->nif,
                'sueldo'=>$trabajador->sueldo,
                'incentivo'=>$trabajador->incentivo,
                'contrato'=>$trabajador->contrato,
                'user'=>$trabajador->user,
                'password'=>$trabajador->password,
                'calle'=>$trabajador->direccione->calle,
                'numero'=>$trabajador->direccione->numero,
                'km'=>$trabajador->direccione->km,
                'bloque'=>$trabajador->direccione->bloque,
                'piso'=>$trabajador->direccione->piso,
                'letra'=>$trabajador->direccione->letra,
                'cp'=>$trabajador->direccione->cp->numero,
                'ciudad'=>$trabajador->direccione->cp->ciudade->ciudad,
                'provincia'=>$trabajador->direccione->cp->ciudade->provincia->nombre,
                'pais'=>$trabajador->direccione->cp->ciudade->provincia->paise->nombre,
                'telefonos'=>$trabajador->telefonos,
                'emails'=>$trabajador->emails,
                'actividades'=>$trabajador->actividades,
                'empresa_id'=>$trabajador->empresa->id
        ];
        return response()->json($data,201);
    }

    /**
     * Actualiza un trabajador
     * @param Request $request
     * @param Trabajadore $trabajador
     * @return array json
     */
    public function update(Request $request, Trabajadore $trabajador)
    {
        $validator = Validator::make($request->all(), [
            'nombre'=>'string|required',
            'apellido1'=>'string|required',
            'apellido2'=>'string|nullable',
            'nif'=>'string|required',
            'direccione_id'=>'numeric|required',
            'foto'=>'nullable',
            'user'=>'string|required',
            'contrato'=>'string|required',
            'sueldo'=>'numeric|required',
            'incentivo'=>'numeric|nullable',
            'empresa_id'=>'numeric|required',
            'remember_token'=>'string|nullable',
            'rol'=>'numeric|required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $trabajador->nombre=$request->nombre;
            $trabajador->apellido1=$request->apellido1;
            $trabajador->apellido2=$request->apellido2;
            $trabajador->nif=$request->nif;
            $trabajador->direccione_id=$request->direccione_id;
            $trabajador->foto=$request->foto;
            $trabajador->user=$request->user;
            if($request->editpassword){
                $trabajador->password=Hash::make($request->password);
            }
            $trabajador->contrato=$request->contrato;
            $trabajador->sueldo=$request->sueldo;
            $trabajador->incentivo=$request->incentivo;
            $trabajador->empresa_id=$request->empresa_id;
            $trabajador->rol=$request->rol;
            $trabajador->remember_token=$request->remember_token;
            $trabajador->save();
            $data = [
                'mensaje' => "Trabajador modficado correctamente",
                'status'  => true,
                'trabajador' => $trabajador
            ];
            return response()->json($data,201);
        }
    }

    /**
     * Borra un trabajador
     * @param Trabajadore $trabajador
     * @return array json
     */
    public function destroy(Trabajadore $trabajador){
        try{
            $trabajador->delete();
            $data=[
                'mensaje'=>'Trabajador elminado correctamente',
                'status'=> true,
                'trabajador'=>$trabajador
            ];
            return  response()->json($data,201);
        }catch(QueryException $e){
            $data=[
                'mensaje'=> 'Imposible eliminar el trabajador, tiene datos asociados',
                'status'=> false,
                'trabajador'=>$e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
