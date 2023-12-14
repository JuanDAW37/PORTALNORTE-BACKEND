<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telefono;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class TelefonosController extends Controller
{
    /**
     * Listado de teléfonos
     * @return array json
    */
    public function index()
    {
        $data=[];
        $telefono = Telefono::all();
        foreach ($telefono as $t){
            $data[]=[
            'id'=>$t->id,
            'telefono'=>$t->numero,
            'cliente'=>$t->cliente,
            'gestor'=>$t->gestor,
            'trabajador'=>$t->trabajadore
        ];
        }
        return response()->json($data,201);
    }

    /**
     * Búsqueda de teléfono
     * @param Request $request
     * @param Telefono $telefono
     * @return array json
    */
    public function buscaTelefono(Request $request)
    {
        $telefono=new Telefono();
        $data=$telefono->buscar($request);
        return response()->json($data,200);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'numero'=>'string|required|unique:telefonos|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'cliente_id'=>'numeric',
            'trabajadore_id'=>'numeric',
            'gestor_id'=>'numeric'
        ]);
        return $validator;
    }

    /**
     * Damos de alta el teléfono
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
            $telefono = new Telefono();
            $telefono->numero = $request->numero;
            if($request->cliente_id){
                $telefono->cliente_id = $request->cliente_id;
            }
            if($request->trabajadore_id){
                $telefono->trabajadore_id = $request->trabajadore_id;
            }
            if($request->gestor_id){
                $telefono->gestor_id = $request->gestor_id;
            }
            $telefono->save();
            $data = [
                'mensaje' => "Teléfono guardado correctamente",
                'status' => true,
                'telefono' => $telefono
            ];
            return response()->json($data,201);
        }
    }

    /**
     *Mostramos los datos del teléfono
     * @param Telefono $telefono
     * @return array json
    */
    public function show(Telefono $telefono)
    {
        $data=[
            'id'=>$telefono->id,
            'numero'=>$telefono->numero,
            'status'=>true,
            'cliente_id'=>$telefono->cliente,
            'trabajador_id'=>$telefono->trabajadore,
            'gestor_id'=>$telefono->gestor,
        ];
        return response()->json($data,201);
    }

    /**
     * Actualizamos el teléfono
     * @param Request $request
     * @param Telefono $telefono
     * @return array json
    */
    public function update(Request $request, Telefono $telefono)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $telefono->numero = $request->numero;
            if($request->cliente_id){
                $telefono->cliente_id = $request->cliente_id;
            }
            if($request->trabajadore_id){
                $telefono->trabajadore_id = $request->trabajadore_id;
            }
            if($request->gestor_id){
                $telefono->gestor_id = $request->gestor_id;
            }
            $telefono->save();
            $data = [
                'mensaje' => "Telefono modificado correctamente",
                "status"=> true,
                'telefono' => $telefono
        ];
            return response()->json($data,201);
        }
    }

    /**
     * Borramos el teléfono
     * @param Telefono $telefono
     * @return array json
     */
    public function destroy(Telefono $telefono){
        try{
            $telefono->delete();
            $data=[
                'mensaje'=>'Telefono elminado correctamente',
                'status'=> true,
                'telefono'=>$telefono
            ];
            return  response()->json($data);
        }catch(QueryException $e){
            $data=[
                'mensaje'=>'No se puede eliminar este telefono porque tiene una relación con otro registro',
                'status'=> false,
                'telefono'=>$e->getMessage()
            ];
            return response()->json($data, 201);
        }
    }
}
