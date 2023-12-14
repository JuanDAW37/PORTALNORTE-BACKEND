<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Paise extends Model
{
    use HasFactory;
    //Para validaciones
    protected $guarded=[];

    /**
     * Relación 1 a N con provincias (1 país tiene N provincias)
    */
    public function provincia():HasMany{
        return $this->hasMany(Provincia::class);
    }

    /**Busca un país
     * @param Request $request
     * @return $data
     */
    public static function buscaPais(Request $request)
    {
        $ps = DB::table('paises')
            ->select('id', 'nombre')
            ->where('nombre', '=', $request->nombre)->get();
        $data=[];
        if ($ps->count()>0) {
            $data=[
                "status"=>true,
                "mensaje"=>'El pais ya existe en la base de datos.',
                "id"=>$ps[0]->id,
                "nombre"=>$ps[0]->nombre,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "status"=>false,
                "mensaje"=>'El pais no existe en la base de datos.',
                "datos"=>'',
                "registros"=>0
            ];
        }
        return $data;
    }
}
