<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use illuminate\Http\Request;

class Email extends Model
{
    use HasFactory;

    /**
     * Relación N a 1 inversa, varios emails pertenecen a 1 cliente
     */
    public function cliente():BelongsTo{
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación N a 1 inversa, varios emails pertenecen a 1 guia
     */
    public function trabajadore():BelongsTo{
        return $this->belongsTo(Trabajadore::class);
    }

    /**
     * Relación N a 1 inversa, varios emails pertenecen a 1 gestor
     */
    public function gestor():BelongsTo{
        return $this->belongsTo(Gestor::class);
    }

    /**
     * Método que busca un email y devuelve datos de la tabla asociada
     * @param \Request $request
     * @return $ps
     */
    public function buscar(Request $request){
        $ps=DB::table('emails')
        ->select('emails.id', 'emails.email', 'clientes.nombre', 'clientes.apellido1', 'clientes.apellido2',
        'trabajadores.nombre', 'trabajadores.apellido1', 'trabajadores.apellido2',
        'gestors.nombre', 'gestors.apellido1', 'gestors.apellido2')
        ->leftJoin('clientes', 'clientes.id','=','emails.cliente_id')
        ->leftJoin('trabajadores', 'trabajadores.id','=','emails.trabajadore_id')
        ->leftJoin('gestors', 'gestors.id','=','emails.gestor_id')
        ->where('email', $request->email)->get();
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
