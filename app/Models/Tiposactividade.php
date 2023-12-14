<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Tiposactividade extends Model
{
    use HasFactory;

    /**
     * Relación 1 a N con actividades (1 tipo de actividad tiene N actividades)
     */
    public function actividades():HasMany{
        return $this->hasMany(Actividade::class);
    }

    /**
     * Método que localiza una tipo de actividad aproximando por su nombre
     * @param Request $request
     * @return $ps
     */
    public function filtrar(Request $request){
        $ps=DB::table('tiposactividades')
        ->select('id', 'tipo')
        ->where('tipo', 'like', '%' . $request->tipo . '%')->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "tipos"=>$ps,
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
}
