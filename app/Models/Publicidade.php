<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Publicidade extends Model
{
    use HasFactory;

    /**
     * Relación 1 a N inversa con Gestor (1 gestor introduce N publicidad)
     */
    public function gestor():BelongsTo
    {
        return $this->belongsTo(Gestor::class);
    }

    /**
     * Método que permite buscar un anuncio publicitario aproximando por su título
     */
    public function buscar($request)
    {
        $ps = DB::table('publicidades')
            ->select(
                'publicidades.id',
                'publicidades.titulo',
                'publicidades.importe',
                'gestors.nombre',
                'gestors.apellido1',
                'gestors.apellido2',
                'gestors.nif'
            )->leftJoin('gestors', 'gestors.id', '=', 'publicidades.gestor_id')
            ->where('titulo', 'like', '%' . $request->titulo . '%')->get();
            $data=[];
            if($ps->count()>0){
                $data=[
                    'mensaje'=>'Hay datos',
                    'status'=>true,
                    "publicidad"=>$ps,
                    "registros"=>$ps->count()
                ];
            }else{
                $data=[
                    "mensaje"=> "No hay datos",
                    "status"=>false,
                    "publicidad"=>'',
                    'registros'=>0
                ];
            }
            return $data;
    }


}
