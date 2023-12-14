<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class EmailController extends Controller
{
    /**
     * Listado de emails
     * @return array json
     */
    public function index()
    {
        $email = Email::all();
        return response()->json($email,201);
    }

    /**
     * Buscar email
     * @param Request $request
     * @return array json
     */
    public function buscaEmail(Request $request)
    {
        $em=new Email();
        $data=$em->buscar($request);
        return response()->json($data,200);
    }

    /**
     * Reglas de validación
     * @param Request $request
     * @return $validator
     *  */
    public function validar(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=>'email|required|unique:emails',
            'cliente_id'=>'numeric',
            'trabajadore_id'=>'numeric',
            'gestor_id'=>'numeric'
        ]);
        return $validator;
    }

    /**
     * Guarda un email
     * @param Request $request
     * @return array json
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);
        if ($validator->fails()) {
            return response()->json([
                'mensaje' => "Errores de validación",
                'status'=>false,
                'errors'=>$validator->messages()
            ],201);
        }else{
            $email = new Email();
            $email->email = $request->email;
            if($request->cliente_id){
                $email->cliente_id = $request->cliente_id;
            }
            if($request->trabajadore_id){
                $email->trabajadore_id = $request->trabajadore_id;
            }
            if($request->gestor_id){
                $email->gestor_id = $request->gestor_id;
            }
            $email->save();
            $data = [
                'mensaje' => "Email guardado correctamente",
                'status'=>true,
                'email' => $email
            ];
            return response()->json($data, 201);
        }
    }

    /**
     * Muestra un email
     * @param Email $email
     * @return array json
     */
    public function show(Email $email)
    {
        $data=[
            'status'=>true,
            'codigo'=>$email->id,
            'email'=>$email->email,
            'cliente'=>$email->cliente,
            'trabajador'=>$email->trabajadore,
            'gestor'=>$email->gestor
        ];
        return response()->json($data);
    }

    /**
     * Actualiza un email
     * @param Request $request
     * @param Email $email
     * @return array json
     */
    public function update(Request $request, Email $email)
    {
        try{
            $validator = $this->validar($request);
            if ($validator->fails()) {
                return response()->json([
                    'mensaje' => "Errores de validación",
                    'status'=>false,
                    'errors'=>$validator->messages()
                ],201);
            }else{
                $email->email = $request->email;
                $email->cliente_id = $request->cliente_id;
                $email->trabajadore_id = $request->trabajadore_id;
                $email->gestor_id = $request->gestor_id;
                $email->save();
                $data = [
                    'mensaje' => "Email modificado correctamente",
                    'status'=>true,
                    'email' => $email
                ];
                return response()->json($data, 201);
            }
        }catch(\Exception $e){
            return response()->json(['error'=>['message'=>$e->getMessage(),'line'=>$e->getLine()]],500);
        }
    }

    /**
     * Elimina un email
     * @param Email $email
     * @return array json
     */
    public function destroy(Email $email)
    {
        try{
            $email->delete();
            $data = [
                'mensaje' => 'Email eliminado correctamente',
                'status'=>true,
                'nombre' => $email
            ];
            return response()->json($data, 201);
        }catch(QueryException $e){
            $data = [
                'mensaje' => 'Imposible borrar el Email, tiene datos asociados',
                'status'=>false,
                'numero' => $e->getMessage()
            ];
            return response()->json($data, 201);
        }

    }
}
