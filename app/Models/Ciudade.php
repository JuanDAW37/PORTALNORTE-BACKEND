<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Ciudade extends Model
{
    use HasFactory;

    /**
     * Relación 1 ciudad tiene muchos códigos postales
     */
    public function cps():HasMany
    {
        return $this->hasMany(Cp::class);
    }

    /**
     * Relación 1 ciudad pertenece a una provincia (inversa)
     */
    public function provincia():BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Método que busca la ciudad por su nombre
     * @param \Request $request
     * @return $data
     */
    public function daCiudad(Request $request)
    {
        $ps = DB::table('ciudades')
            ->select('ciudades.id as ciudad_id','ciudades.ciudad as nombre', 'provincias.id as provincia_id',
            'provincias.nombre as provincia', 'paises.nombre as pais')
            ->leftJoin('provincias', 'provincias.id', '=', 'ciudades.provincia_id')
            ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
            ->where('ciudades.ciudad', '=', $request->ciudad)->limit(1)->get();
        $data=[];
        if ($ps->count()>0) {
            $data=[
                "status"=>true,
                "mensaje"=>'La ciudad ya existe en la base de datos.',
                "id"=>$ps[0]->ciudad_id,
                "ciudad"=>$ps[0]->nombre,
                "provincia_id"=>$ps[0]->provincia_id,
                "provincia"=>$ps[0]->provincia,
                "pais"=>$ps[0]->pais,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "status"=>false,
                "mensaje"=>'La ciudad no existe en la base de datos.',
                "data"=>'',
                "registros"=>0
            ];
        }
        return $data;
    }
}
