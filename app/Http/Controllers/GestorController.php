<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gestor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class GestorController extends Controller
{
    /**
     * Listado del gestor.
     * @return array json
     */
    public function index()
    {
        $data = [];
        $gestor = Gestor::all();
        foreach ($gestor as $g) {
            $data[] = [
                'id' => $g->id,
                'nombre' => $g->nombre,
                'apellido1' => $g->apellido1,
                'apellido2' => $g->apellido2,
                'nif' => $g->nif,
                'calle' => $g->direccione->calle,
                'numero' => $g->direccione->numero,
                'km' => $g->direccione->km,
                'bloque'=>$g->direccione->bloque,
                'piso'=>$g->direccione->piso,
                'letra'=>$g->direccione->letra,
            ];
        }
        return response()->json($data, 201);
    }

    /**
     *Filtra el gestor por nombre y/o apellido1 y/o apellido2 y/o nif
     * @param Request $request
     * @return array json
     */
    public function filtraGestor(Request $request){
        $ges = new Gestor();
        $data = $ges->filtrar($request);
        return response()->json($data, 200);
    }

    /**
     * Verifica que no haya más clientes con el mismo NIF, al mismo tiempo que por el nombre de usuario
     */
    public function nifUserGest(Request $request){
        $data=Gestor::nifUser($request);
        return response()->json($data, 201);
    }

    /**
     * Sube una foto de un gestor a la carpeta ./public/gestors
     * @param Request $request
     * @return string
     */
    public function fotoGestor(Request $request){
        if ($request->hasFile('file')){
            $file=$request->file('file');
            $fileName=$file->getClientOriginalName();
            $filename=pathinfo($fileName, PATHINFO_FILENAME);
            $name_file=str_replace(' ', '_', $filename);
            $extension=$file->getClientOriginalExtension();
            $newFileName=date('His').'-'.$name_file.'.'.$extension;
            $file->move(public_path('gestors/'), $newFileName);
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
            'foto' => 'nullable',
            'user'=>'string|required',
            'password'=>'string|required',
            'contrato'=>'string|required',
            'remember_token'=>'string|nullable',
            'sueldo'=>'numeric|required',
            'rol'=>'numeric|required',
        ]);
        return $validator;
    }

    /**
     * Guarda los datos del gestor.
     * @param Request $request
     * @return array json
     */
    public function store(Request $request)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            $data=[
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ];
            return response()->json($data,404);
        }else{
            $gestor = new Gestor;
            $gestor->nif = $request->nif;
            $gestor->nombre = $request->nombre;
            $gestor->apellido1 = $request->apellido1;
            $gestor->apellido2 = $request->apellido2;
            $gestor->direccione_id = $request->direccione_id;
            $gestor->foto = $request->foto;
            $gestor->user = $request->user;
            $gestor->password = Hash::make($request->password);
            $gestor->contrato = $request->contrato;
            $gestor->sueldo = $request->sueldo;
            $gestor->remember_token = $request->remember_token;
            $gestor->save();
            $data = [
                'mensaje:' => 'Gestor creado correctamente',
                'status'=>true,
                'id' => $gestor->id,
                'nombre' => $gestor->nombre,
                'apellido1' => $gestor->apellido1,
            ];
            return response()->json($data, 201);
        }
    }
    /**
    * Sube la foto del gestor al servidor
    * @param Request $request
    */
    public function subeFoto(Request $request){
        if($request->hasFile("foto")){
            $imagen = $request->file("foto");
            $nombreimagen = $imagen->getClientOriginalName();
            $ruta = public_path("app/gestors/fotos-gestor");
            copy($imagen->getRealPath(),$ruta.$nombreimagen);
        }
    }

    /**
     * Visualiza los datos del gestor.
     * @param Gestor $gestor
     * @return array json
     */
    public function show(Gestor $gestor)
    {
        $data = [
            'nombre' => $gestor->nombre,
            'apellido1'=>$gestor->apellido1,
            'apellido2'=>$gestor->apellido2,
            'nif'=>$gestor->nif,
            'user' => $gestor->user,
            'password' => $gestor->password,
            'contrato' => $gestor->contrato,
            'sueldo' => $gestor->sueldo,
            'token' => $gestor->remember_token,
            'direccione_id' => $gestor->direccione_id,
            'calle' => $gestor->direccione->calle,
            'km' => $gestor->direccione->km,
            'numero' => $gestor->direccione->numero,
            'bloque' => $gestor->direccione->bloque,
            'piso' => $gestor->direccione->piso,
            'letra' => $gestor->direccione->letra,
            'cp'=>$gestor->direccione->cp->numero,
            'ciudad'=>$gestor->direccione->cp->ciudade->ciudad,
            'provincia'=>$gestor->direccione->cp->ciudade->provincia->nombre,
            'pais'=>$gestor->direccione->cp->ciudade->provincia->paise->nombre,
            'telefono' => $gestor->telefonos,
            'email' => $gestor->emails,
            'actividades' => $gestor->actividades,
            'publicidades' => $gestor->publicidades,
        ];
        return response()->json($data);
    }

    /**
     * Actualiza los datos del gestor.
     * @param Request $request
     * @param Gestor $gestor
     * @return array json
     */
    public function update(Request $request, Gestor $gestor)
    {
        $validator = Validator::make($request->all(), [
            'nombre'=>'string|required',
            'apellido1'=>'string|required',
            'apellido2'=>'string|nullable',
            'nif'=>'string|required',
            'direccione_id'=>'numeric|required',
            'foto' => 'nullable',
            'user'=>'string|required',
            'contrato'=>'string|required',
            'remember_token'=>'string|nullable',
            'sueldo'=>'numeric|required',
            'rol'=>'numeric|required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $gestor->nif = $request->nif;
            $gestor->nombre = $request->nombre;
            $gestor->apellido1 = $request->apellido1;
            $gestor->apellido2 = $request->apellido2;
            $gestor->direccione_id = $request->direccione_id;
            $gestor->foto = $request->foto;
            $gestor->user = $request->user;
            if($request->editpassword){
                $gestor->password =$gestor->password = Hash::make($request->password);
            }
            $gestor->contrato = $request->contrato;
            $gestor->sueldo = $request->sueldo;
            $gestor->remember_token = $request->remember_token;
            $gestor->save();
            $data = [
                'mensaje' => 'Gestor actualizado correctamente',
                'status'=>true,
                'id' => $gestor->id,
                'nombre' => $gestor->nombre,
                'apellido1' => $gestor->apellido1,
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Borra el gestor.
     * @param Gestor $gestor
     * @return  array json
     */
    public function destroy(Gestor $gestor)
    {
        try{
            $gestor->delete();
            $data = [
                'mensaje:' => 'Gestor eliminado correctamente',
                'status'=>true,
                'cliente' => $gestor
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar el gestor, tiene datos asociados',
                'status'=>false,
                'empresa' => $e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
