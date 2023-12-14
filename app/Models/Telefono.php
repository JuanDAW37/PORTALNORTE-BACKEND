<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Telefono extends Model
{
    use HasFactory;

    /**
     * Relación N a 1 inversa, varios telefonos pertenecen a 1 cliente
     */
    public function cliente():BelongsTo{
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación N a 1 inversa, varios telefonos pertenecen a 1 guia
     */
    public function trabajadore():BelongsTo{
        return $this->belongsTo(Trabajadore::class);
    }

    /**
     * Relación N a 1 inversa, varios telefonos pertenecen a 1 gestor
     */
    public function gestor():BelongsTo{
        return $this->belongsTo(Gestor::class);
    }

    /**
     * Método que busca un telefono y devuelve datos de la tabla asociada
     * @param \Request $request
     * @return $ps
     */
    public function buscar(Request $request){
        $ps=DB::table('telefonos')
        ->select('telefonos.id as telef_id', 'telefonos.numero as telefono', 'clientes.id as client_id',
        'clientes.nombre as client_nom', 'clientes.apellido1 as client_ape1', 'clientes.apellido2 as client_ape2',
        'trabajadores.id as trabaj_id', 'trabajadores.nombre as trabaj_nom', 'trabajadores.apellido1 as trabaj_ape1',
        'trabajadores.apellido2 as trabaj_ape2', 'gestors.id as gestors_id', 'gestors.nombre as gestors_nom',
        'gestors.apellido1 as gestors_ape1', 'gestors.apellido2 as gestors_ape2')
        ->leftJoin('clientes', 'clientes.id','=','telefonos.cliente_id')
        ->leftJoin('trabajadores', 'trabajadores.id','=','telefonos.trabajadore_id')
        ->leftJoin('gestors', 'gestors.id','=','telefonos.gestor_id')
        ->where('telefonos.numero', $request->telefono)->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "data"=>$ps,
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
