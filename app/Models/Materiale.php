<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Materiale extends Model
{
    use HasFactory;

    /**
     * Relación N a M con actividades (se usan tablas pivote)
    */
    public function actividades():BelongsToMany{
        return $this->belongsToMany(Actividade::class);
    }

    /**
     * Método para localizar materiales aproximando por su nombre
     * @param \Request $request
     * @return $ps
     */
    public function buscar(Request $request){
        $ps = DB::table('materiales')
        ->select('materiales.id as id', 'materiales.nombre as nombre')
        ->where("nombre", "like","%".$request->nombre."%")->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                'materiales'=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "materiales"=>'',
                'registros'=>0
            ];
        }
        return $data;
    }
}
