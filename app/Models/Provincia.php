<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Provincia extends Model
{
    use HasFactory;

    /**
     * Relación N a 1 (1 provincia pertenece a N países) (inversa)
    */
    public function paise():BelongsTo{
        return $this->belongsTo(Paise::class);
    }

    /**
     * Relación 1 a N (1 provincia tiene N ciudades)
     */
    public function ciudades():HasMany{
        return $this->hasMany(Ciudade::class);
    }

    /**Busca la provincia
     * @param Request $request
     * @return $data
     */
    public static function buscaProvincia(Request $request)
    {
        $ps = DB::table('provincias')
            ->select('provincias.id as id','provincias.codigo as codigo', 'provincias.nombre as nombre',
                    'provincias.paise_id as paise_id', 'paises.nombre as pais')
            ->leftJoin('paises', 'paises.id', '=', 'provincias.paise_id')
            ->where('provincias.nombre', '=', $request->nombre)->get();
        $data=[];
        if ($ps->count()>0) {
            $data=[
                "status"=>true,
                "mensaje"=>'La provincia ya existe en la base de datos.',
                "id"=>$ps[0]->id,
                "codigo"=>$ps[0]->codigo,
                "nombre"=>$ps[0]->nombre,
                "paise_id"=>$ps[0]->paise_id,
                "pais"=>$ps[0]->pais,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "status"=>false,
                "mensaje"=>'La provincia no existe en la base de datos.',
                "data"=>'',
                "registros"=>0
            ];
        }
        return $data;
    }
}
