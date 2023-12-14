<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class EmpresaController extends Controller
{
    /**
     * Visualizado del listado de la tabla
     * @return array json
     */
    public function index()
    {
        $data = [];
        $empresa = Empresa::all();
        foreach ($empresa as $empre) {
            $data[] = ['id' => $empre->id,
                'nombre' => $empre->nombre,
                'nif' => $empre->nif,
                'direccion' => $empre->direccione];
        }
        return response()->json($data, 201);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre'=>'string|required',
            'nif'=>'string|required',
            'direccione_id'=>'numeric|required',
        ]);
        return $validator;
    }

    /**
     * Alta de un registro en la tabla
     * @param Request $empresa
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
            $empresa = new Empresa;
            $empresa->nombre = $request->nombre;
            $empresa->nif = $request->nif;
            $empresa->direccione_id = $request->direccione_id;
            $empresa->save();
            $data = [
                'mensaje' => "Empresa creada perfectamente",
                'status'=>true,
                'empresa' => $empresa
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Visualización de un registro específico
     * @param Empresa $empresa
     * @return array json
     */
    public function show(Empresa $empresa)
    {
        $data = [
            'id' => $empresa->id,
            'nombre'=>$empresa->nombre,
            'nif'=>$empresa->nif,
            'trabajadores' => $empresa->trabajadores,
            'calle' => $empresa->direccione->calle,
            'km' => $empresa->direccione->km,
            'numero' => $empresa->direccione->numero,
            'bloque' => $empresa->direccione->bloque,
            'piso' => $empresa->direccione->piso,
            'letra'=> $empresa->direccione->letra,
            'direccione_id'=>$empresa->direccione_id,
            'cp' => $empresa->direccione->cp->numero,
            'ciudad' => $empresa->direccione->cp->ciudade->ciudad,
            'provincia' => $empresa->direccione->cp->ciudade->provincia->nombre,
            'pais' => $empresa->direccione->cp->ciudade->provincia->paise->nombre
        ];
        return response()->json($data, 201);
    }

    /**
     * Actualización de un registro específico en la tabla.
     * @param Request $request
     * @param Empresa $empresa
     * @return array json
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validator=$this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje'=>'Errores de validación',
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $empresa->nombre = $request->nombre;
            $empresa->nif = $request->nif;
            $empresa->direccione_id = $request->direccione_id;
            $empresa->save();
            $data = [
                'mensaje' => "Empresa actualizada correctamente",
                'status'=>true,
                'empresa' => $empresa
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Eliminación de un registro específico de la tabla.
     * @param Empresa $empresa
     * @return array json
     */
    public function destroy(Empresa $empresa)
    {
        try{
            $empresa->delete();
            $data = [
                'mensaje' => 'Empresa eliminada',
                'status'=>true,
                'empresa' => $empresa
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar la empresa, tiene datos asociados',
                'status'=>false,
                'empresa' => $e->getMessage()
            ];
            return response()->json($data, 201);
        }

    }
}
