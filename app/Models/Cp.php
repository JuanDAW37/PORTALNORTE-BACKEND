<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Cp extends Model
{
    use HasFactory;

    /**
     * Relación 1 código postal pertenece a 1 ciudad (inversa)
     */
    public function ciudade():BelongsTo{
        return $this->belongsTo(Ciudade::class);
    }

    /**
     * Relación 1 código postal tiene muchas direcciones
     */
    public function direcciones():HasMany{
        return $this->hasMany(Direccione::class);
    }

    /**
     * Busca el código postal aproximando por su número
     * @param $request
     * @return $ps
     */
    public function buscarCp(Request $request){
        $ps=DB::table('cps')
        ->select('cps.id as id', 'cps.numero as numero', 'ciudades.id as ciudad_id','ciudades.ciudad as ciudad',
        'provincias.nombre as provincia', 'paises.nombre as pais')
        ->leftJoin('ciudades', 'ciudades.id', '=', 'cps.ciudade_id')
        ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
        ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
        ->where('cps.numero','=', $request->numero)->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                "status"=>true,
                "mensaje"=>'El CP ya existe en la base de datos.',
                "id"=>$ps[0]->id,
                "numero"=>$ps[0]->numero,
                "ciudade_id"=>$ps[0]->ciudad_id,
                "ciudad"=>$ps[0]->ciudad,
                "provincia"=>$ps[0]->provincia,
                "pais"=>$ps[0]->pais,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "status"=>false,
                "mensaje"=>'El CP no existe en la base de datos.',
                "data"=>'',
                "registros"=>0
            ];
        }
        return $data;
    }
}
