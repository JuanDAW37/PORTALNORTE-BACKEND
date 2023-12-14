<?php

namespace App\Http\Controllers;

session_start();

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Gestor;
use App\Models\Trabajadore;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    /**
     * Comprueba si ya existe algún gestor
     * @return array
     */
    public function contarGestor():array{
        $gestor=Gestor::count();
        if($gestor>0){
            return $data=[
                'status'=>true
            ];
        }
        else{
            return $data=[
                'status'=>false
            ];
        }
    }

    /**
     * Alta de usuario gestor ya que no existe gestor en Gestors
     * @param Request $request
     * @return array json
     */
    public function registro(Request $request)
    {
        //validar los campos
        $validator = Validator::make($request->all(), [
            'nombre' => 'string|required',
            'apellido1' => 'string|required',
            'apellido2' => 'string|nullable',
            'nif' => 'string|required',
            'user' => 'string|required',
            'password' => 'string|required',
            'contrato'=>'string|required',
            'sueldo'=>'numeric|required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $gestor = new Gestor;
            $gestor->nif = $request->nif;
            $gestor->nombre = $request->nombre;
            $gestor->apellido1 = $request->apellido1;
            $gestor->apellido2 = $request->apellido2;
            $gestor->user = $request->user;
            $gestor->password = Hash::make($request->password);
            $gestor->contrato = $request->contrato;
            $gestor->sueldo = $request->sueldo;
            $gestor->save();

        $data=['gestor'=>$gestor,
            'mensaje'=>'Primer gestor creado!!!',
            'status'=>true];
        return response()->json($data,200);
    }

    /**
     * Login de Gestor o Guía
     * @param Request $request
     * @return array json
     */
    public function login(Request $request)
    {
        //validar los campos
        $validator = Validator::make($request->all(), [
            'user' => 'string|min:8|max:25',
            'password' => 'string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $gestor = Gestor::where('user', $request->user)->first();

        if($gestor && Hash::check($request->password, $gestor->password)){
            $token = $gestor->createToken('auth_token')->plainTextToken;
            $token="Bearer ".$token;
            $this->tokenAdministrador($token, $request->user);
            return response()->json([
                    'datos' => $gestor,
                    'token' => $token,
                    'token_tipo' => 'Bearer',
                    'status' => true,
                    'rol'=>$gestor->rol
                    ], 200);
        }else{
            $trabajador = Trabajadore::where('user', $request->user)->first();
            if($trabajador && Hash::check($request->password, $trabajador->password)){
                $token = $trabajador->createToken('auth_token')->plainTextToken;
                $token="Bearer ".$token;

                $this->tokenTrabajador($token, $request->user);
                return response()->json([
                    'datos' => $trabajador,
                    'token' => $token,
                    'token_tipo' => 'Bearer',
                    'status' => true,
                    'rol'=>$trabajador->rol
                    ], 200);

            }else{
                return response()->json(
                    [
                        'data'=>false,
                        'mensaje' => "Usuario y/o contraseña incorrectos",
                    ]
                ,200);
            }
        }
    }

    /**
     * Cierra la sesión y regenera el token
     */
    public function logout()
    {
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            auth()->user()->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => "Sesión cerrada correctamente"
            ], 200);
    }

    /**
     * Actualiza el token del trabajador
     * @param $token
     * @param $user
     * @return void
     */
    public function tokenTrabajador($token, $user):void{
        DB::table('trabajadores')->where('user', $user)->update(['remember_token'=>$token]);
    }

    /**
     * Actualiza el token del administrador
     * @param $token
     * @param $user
     * @return void
     */
    public function tokenAdministrador($token, $user):void{
        DB::table('gestors')->where('user', $user)->update(['remember_token'=>$token]);
    }
}
