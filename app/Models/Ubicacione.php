<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ubicacione extends Model
{
    use HasFactory;

    /**
     * Relación N a M con actividades (se usan tablas pivote)
     */
    public function actividades():BelongsToMany{
        return $this->belongsToMany(Actividade::class);
    }

    /**
     * Método que busca ubicaciones aproximando por su nombre
     */
    public function buscar(Request $request){
        $ps=DB::table('ubicaciones')
        ->select('id', 'nombre', 'lat', 'lon')
        ->where('nombre', 'like', '%' . $request->nombre . '%')->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "ubicaciones"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "ubicaciones"=>'',
                'registros'=>0
            ];
        }
        return $data;
    }
}
