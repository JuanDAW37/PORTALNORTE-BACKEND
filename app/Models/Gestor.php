<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\FacadesAuth\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Gestor extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'nif',
        'direccione_id',
        'foto',
        'usuario',
        'pass',
    ];

    protected $hidden=[
        'pass',
        'remember_token'
    ];

    /**
     * Relación 1 a N con actividades (1 gestor introduce N actividades)
     */
    public function actividades(){
        return $this->hasMany(Actividade::class);
    }

    /**
     * Relación 1 a N con emails (1 gestor puede tener N emails)
     */
    public function emails(){
        return $this->hasMany(Email::class);
    }

    /**
     * Relación 1 a 1 con direcciones (1 gestor está en 1 dirección)
     */
    public function direccione(){
        return $this->belongsTo(Direccione::class);
    }


    /**
     * Relación 1 a N con publicidades (1 gestor introduce N publicidades)
     */
    public function publicidades():HasMany{
        return $this->hasMany(Publicidade::class);
    }

    /**
     * Relación 1 a N con telefonos (1 gestor tiene N telefonos)
     */
    public function telefonos():HasMany{
        return $this->hasMany(Telefono::class);
    }
    
    /**
     * Método que permite buscar un trabajador aproximando por su nif y/o nombre y/o apellido1 y/o apellido2
     * @param Request $request
     * @return $ps
     */
    public function filtrar(Request $request)
    {
        $campos = [
            'gestors.id',
            'gestors.nombre as nombre',
            'gestors.apellido1',
            'gestors.apellido2',
            'gestors.nif',
            'direcciones.calle as calle',
            'direcciones.numero as numero',
            'direcciones.km as km',
            'direcciones.bloque as bloque',
            'direcciones.piso as piso',
            'direcciones.letra as letra',
            'cps.numero as cp',
            'ciudades.ciudad as ciudad',
            'provincias.nombre as provincia',
            'paises.nombre as pais'
        ];
        if ($request->nombre) {
            $ps = DB::table('gestors')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'gestors.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('gestors.nombre', 'like', '%' . $request->nombre . '%')->get();
        }
        if ($request->nif) {
            $ps = DB::table('gestors')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'gestors.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('gestors.nif', 'like', '%' . $request->nif . '%')->get();
        }
        if ($request->apellido1) {
            $ps = DB::table('gestors')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'gestors.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('gestors.apellido1', 'like', '%' . $request->apellido1 . '%')->get();
        }
        if ($request->apellido2) {
            $ps = DB::table('gestors')
                ->select($campos)
                ->leftJoin('direcciones', 'direcciones.id', '=', 'gestors.direccione_id')
                ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')
                ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
                ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
                ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
                ->where('gestors.apellido2', 'like', '%' . $request->apellido2 . '%')->get();
        }
        $data=[];
        if($ps->count()>0){
            $data=[
                "status"=>true,
                "mensaje"=>"Hay datos",
                "gestors"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "status"=>false,
                "mensaje"=>"No hay datos",
                "gestors"=>'',
                "registros"=>0
            ];
        }
        return $data;
    }

    /**
     * Verifica si existe o no el nif y/o el nombre del gestor
     * @param Request $request
     */
    public static function nifUser(Request $request){
        if($request->nif){
            $data=Gestor::where('nif', '=', $request->nif);
        }
        if($request->user){
            $data=Gestor::where('user', '=', $request->user);
        }
        if($data->count()>0){
            $datos=[
                "mensaje"=>"Ya existe un gestor registrado en la base de datos.",
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
