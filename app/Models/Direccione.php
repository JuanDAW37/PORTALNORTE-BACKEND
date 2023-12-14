<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Direccione extends Model
{
    use HasFactory;

    /**
     * Relación 1 a 1 con clientes
     */
    public function clientes():HasMany{
        return $this->hasMany(Cliente::class);
    }

    /**
     * Relación 1 a 1 con empresa
     */
    public function empresa():HasOne{
        return $this->hasOne(Empresa::class);
    }

    /**
     * Relación 1 a 1 con gestors
     */
    public function gestor():HasOne{
        return $this->hasOne(Gestor::class);
    }

    /**
     * Relación 1 a 1 con trabajadores
     */
    public function trabajadores():HasMany{
        return $this->hasMany(Trabajadore::class);
    }

    /**
     * Relación inversa N a 1 con Códigos postales
     */
    public function cp():BelongsTo{
        return $this->belongsTo(Cp::class);
    }

    /**Busca por direccion
     * @param Request $request
     * return $ps
     */
    public static function buscaDireccion(Request $request){
        $ps=DB::table('direcciones')->select('direcciones.id as dir_id', 'direcciones.calle', 'direcciones.km', 'direcciones.numero',
        'direcciones.bloque', 'direcciones.piso', 'direcciones.letra', 'cps.id as cps','cps.numero as cp', 'ciudades.ciudad as ciudad',
        'provincias.nombre as provincia', 'paises.nombre as pais', 'direcciones.cp_id')
        ->leftJoin('cps', 'cps.id', '=', 'direcciones.cp_id')->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
        ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
        ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
        ->where('direcciones.calle', $request->calle)->where('direcciones.numero', $request->numero)
        ->where('direcciones.km', $request->km)->where('direcciones.bloque', $request->bloque)
        ->where('direcciones.piso', $request->piso)->where('direcciones.letra', $request->letra)->limit(1)->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                "status"=>true,
                "mensaje"=>'La dirección ya existe en la base de datos.',
                "id"=>$ps[0]->dir_id,
                "calle"=>$ps[0]->calle,
                "km"=>$ps[0]->km,
                "numero"=>$ps[0]->numero,
                "bloque"=>$ps[0]->bloque,
                "piso"=>$ps[0]->piso,
                "letra"=>$ps[0]->letra,
                "cp_id"=>$ps[0]->cps,
                "cp"=>$ps[0]->cp,
                "ciudad"=>$ps[0]->ciudad,
                "provincia"=>$ps[0]->provincia,
                "pais"=>$ps[0]->pais,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "status"=>false,
                "mensaje"=>'La dirección no existe en la base de datos.',
                "data"=>'',
                "registros"=>0
            ];
        }
        return $data;
    }
}
