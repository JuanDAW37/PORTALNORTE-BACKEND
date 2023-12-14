<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class ClientesController extends Controller
{
    /**
     * Listado de clientes
     * @return array json
     */
    public function index()
    {
        $clientes = Cliente::all();
        $data = [];
        foreach ($clientes as $cliente) {
            $data[] = [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'apellido1' => $cliente->apellido1,
                'apellido2' => $cliente->apellido2,
                'nif' => $cliente->nif,
                'calle' => $cliente->direccione->calle,
                'km' => $cliente->direccione->km,
                'numero' => $cliente->direccione->numero,
                'bloque' => $cliente->direccione->bloque,
                'piso' => $cliente->direccione->piso,
                'letra'=>$cliente->direccione->letra
            ];
        }
        return response()->json($data, 201);
    }

    /**
     * Búsqueda por aproximación de cliente por nif y/o nombre y/o apellido1 y apellido2
     * @param Request $request
     * @param Cliente $cliente
     * @return array json
     */
    public function filtraClient(Request $request)
    {
        $cli = new Cliente();
        $data = $cli->buscarCliente($request);
        return response()->json($data, 200);
    }

    /**
     * Verifica que no haya más clientes con el mismo NIF, al mismo tiempo que por el nombre de usuario
     */
    public function nifUserCli(Request $request){
        $data=Cliente::nifUser($request);
        return response()->json($data, 201);
    }

    /**
     * Sube una foto de un cliente a la carpeta ./public/clientes
     * @param Request $request
     * @return
     */
    public function fotoCliente(Request $request){
        $data=[];
        if ($request->hasFile('file')){
            $file=$request->file('file');
            $fileName=$file->getClientOriginalName();
            $filename=pathinfo($fileName, PATHINFO_FILENAME);
            $name_file=str_replace(' ', '_', $filename);
            $extension=$file->getClientOriginalExtension();
            $newFileName=date('His').'-'.$name_file.'.'.$extension;
            $file->move(public_path('clientes/'), $newFileName);
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
            'nif'=>'string|required',
            'nombre'=>'string|required',
            'apellido1'=>'string|required',
            'apellido2'=>'string|nullable',
            'direccione_id'=>'numeric|required',
            'foto'=>'nullable',
            'user'=>'string|required',
            'password'=>'string|required',
            'baja'=>'string|nullable',
            'bonificacion'=>'numeric|nullable',
            'rol'=> 'numeric|required',
        ]);
        return $validator;
    }

    /**
     * Almacenamiento de un cliente
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
            ],200);
        }else{
            $timestamp = strtotime($request->baja);
            $fecha = date("Y-m-d", $timestamp);
            $cliente = new Cliente();
            $cliente->nombre = $request->nombre;
            $cliente->apellido1 = $request->apellido1;
            $cliente->apellido2 = $request->apellido2;
            $cliente->nif = $request->nif;
            $cliente->direccione_id = $request->direccione_id;
            $cliente->foto = $request->foto;
            $cliente->user = $request->user;
            $cliente->password = Hash::make($request->password);
            $cliente->baja=$fecha;
            $cliente->bonificacion=$request->bonificacion;
            $cliente->rol=$request->rol;
            $cliente->save();
            $data = [
                'mensaje' => 'Cliente creado correctamente',
                'status'=>true,
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'apellido1' => $cliente->apellido1,
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Mostrar los datos de un cliente
     * @param Cliente $cliente
     * @return array json
     * */
    public function show(Cliente $cliente)
    {
        $data = [
            'id'=>$cliente->id,
            'nombre' => $cliente->nombre,
            'apellido1' => $cliente->apellido1,
            'apellido2' => $cliente->apellido2,
            'nif' => $cliente->nif,
            'direccione_id' => $cliente->direccione_id,
            'calle'=>$cliente->direccione->calle,
            'km'=>$cliente->direccione->km,
            'numero'=>$cliente->direccione->numero,
            'bloque'=>$cliente->direccione->bloque,
            'piso'=>$cliente->direccione->piso,
            'letra'=>$cliente->direccione->letra,
            'cp' => $cliente->direccione->cp->numero,
            'ciudad' => $cliente->direccione->cp->ciudade->ciudad,
            'provincia' => $cliente->direccione->cp->ciudade->provincia->nombre,
            'pais' => $cliente->direccione->cp->ciudade->provincia->paise->nombre,
            'foto' => $cliente->foto,
            'user' => $cliente->user,
            'password' => $cliente->password,
            'baja' => $cliente->baja,
            'bonificacion' => $cliente->bonificacion,
            'rol' => $cliente->rol,
            'facturas' => $cliente->facturas,
            'reservas' => $cliente->reservas,
            'email' => $cliente->emails,
            'telefono' => $cliente->telefonos
        ];
        return response()->json($data);
    }

    /**
     * Actualizar un cliente
     * @param Request $request
     * @param Cliente $cliente
     * @return array json
     * */
    public function update(Request $request, Cliente $cliente)
    {
        $validator = Validator::make($request->all(), [
            'nif'=>'string|required',
            'nombre'=>'string|required',
            'apellido1'=>'string|required',
            'apellido2'=>'string|nullable',
            'direccione_id'=>'numeric|required',
            'foto'=>'nullable',
            'user'=>'string|required',
            'baja'=>'string|nullable',
            'bonificacion'=>'numeric|nullable',
            'rol'=> 'numeric|required',
        ]);
        try{
            if ($validator->fails()) {
                return response()->json([
                    'mensaje'=>'Errores de validación',
                    'status'=>false,
                    'error'=>$validator->messages()
                ],201);
            }else{
                $timestamp = strtotime($request->baja);
                $fecha = date("Y-m-d", $timestamp);
                $cliente->nombre = $request->nombre;
                $cliente->apellido1 = $request->apellido1;
                $cliente->apellido2 = $request->apellido2;
                $cliente->nif = $request->nif;
                $cliente->direccione_id = $request->direccione_id;
                $cliente->foto = $request->foto;
                $cliente->user = $request->user;
                if($request->editpassword){
                    $cliente->password = Hash::make($request->password);
                }
                $cliente->baja=$fecha;
                $cliente->bonificacion=$request->bonificacion;
                $cliente->rol=$request->rol;
                $cliente->save();
                $data = [
                    'mensaje' => 'Cliente actualizado correctamente',
                    'status'=>true,
                    'baja' => $request->baja,
                    'cliente' => $cliente
                ];
                return response()->json($data, 201);
            }
        }catch(QueryException $e){
            $data=[
                'mensaje'=>'Error al dar de baja el cliente',
                'status'=>false,
                'cliente'=>$e->getMessage()
            ];
            return response()->json($data,200);
        }
    }
}
