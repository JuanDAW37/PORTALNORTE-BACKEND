<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Trabajadore extends Model
{
    use HasFactory, HasApiTokens, HasFactory, Notifiable;

    /**
     * Relación 1 a N inversa con empresas (varios trabajadores pertenecen a 1 empresa)
     */public function empresa():BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación N a M con actividades (se usan tablas pivote)
     */
    public function actividades():BelongsToMany
    {
        return $this->belongsToMany(Actividade::class);
    }

    /**
     * Relación 1 a N con emails (1 trabajador puede tener de 1 a N emails)
     */
    public function emails():HasMany
    {
        return $this->hasMany(Email::class);
    }

    /**
     * Relación 1 a N con telefonos (1 trabajador puede tener de 1 a N teléfonos)
     */
    public function telefonos():HasMany
    {
        return $this->hasMany(Telefono::class);
    }

    /**
     * Relación 1 a N con direcciones (1 trabajador puede tener de 1 a N direcciones)
     */
    public function direccione():BelongsTo
    {
        return $this->belongsTo(Direccione::class);
    }

    /**
     * Método que permite buscar un trabajador aproximando por su nif y/o nombre y/o apellido1 y/o apellido2
     * @param Request $request
     * @return $ps
     */
    public function buscar(Request $request)
    {
        $campos = [
            'trabajadores.id',
            'trabajadores.nombre as nombre',
            'trabajadores.apellido1',
            'trabajadores.apellido2',
            'trabajadores.nif',
            'direcciones.calle',
            'direcciones.numero',
            'direcciones.km',
            'direcciones.bloque',
            'direcciones.piso',
            'direcciones.letra',
            'cps.numero as cp',
            'ciudades.ciudad',
            'provincias.nombre as provincia',
            'paises.nombre as pais'
        ];
        if ($request->nombre) {
            $ps = DB::table('trabajadores')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'trabajadores.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('trabajadores.nombre', 'like', '%' . $request->nombre . '%')->get();
        }
        if ($request->nif) {
            $ps = DB::table('trabajadores')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'trabajadores.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('trabajadores.nif', 'like', '%' . $request->nif . '%')->get();
        }
        if ($request->apellido1) {
            $ps = DB::table('trabajadores')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'trabajadores.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('trabajadores.apellido1', 'like', '%' . $request->apellido1 . '%')->get();
        }
        if ($request->apellido2) {
            $ps = DB::table('trabajadores')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'trabajadores.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('trabajadores.apellido2', 'like', '%' . $request->apellido2 . '%')->get();
        }
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "trabajadores"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "trabajadores"=>'',
                'registros'=>0
            ];
        }
        return $data;
    }

    /**
     * Método que se usa en el login para verificar si existe o no el gestor
     * @param Request $datos
     * @return $ps
     */
    public function login(Request $request){
        $ps=DB::table('trabajadores')
        ->select('user', 'password')
        ->where('user', $request->user)->where('password', password_hash($request->password, PASSWORD_DEFAULT))->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "data"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "data"=>'',
                'registros'=>0
            ];
        }
        return $data;
    }

    /**
     * Verifica si existe o no el nif y/o el nombre del trabajador
     * @param Request $request
     */
    public static function nifUser(Request $request){
        if($request->nif){
            $data=Trabajadore::where('nif', '=', $request->nif);
        }
        if($request->user){
            $data=Trabajadore::where('user', '=', $request->user);
        }
        if($data->count()>0){
            $datos=[
                "mensaje"=>"Ya existe un trabajador registrado en la base de datos.",
                "status"=>true
            ];
            return $datos;
        }else{
            $datos=[
                "mensaje"=>"No se ha encontrado ningun registro.",
                "status"=>false
            ];
            return $datos;
        }
    }
}
