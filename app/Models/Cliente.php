<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class Cliente extends Model
{
    use HasFactory;

    /**
     * Relación 1 cliente tiene N facturas
     */
    public function facturas():HasMany{
        return $this->hasMany(Factura::class);
    }

    /**
     * Relación 1 cliente tiene N reservas
     */
    public function reservas():HasMany{
        return $this->hasMany(Reserva::class);
    }

    /**
     * Relación 1 cliente tiene 1 dirección
     */
    public function direccione():BelongsTo{
        return $this->belongsTo(Direccione::class);
    }

    /**
     * Relación 1 cliente tiene N emails
     */
    public function emails():HasMany{
        return $this->hasMany(Email::class);
    }

    /**
     * Relación 1 cliente tiene N telefonos
     */
    public function telefonos():HasMany{
        return $this->hasMany(Telefono::class);
    }

    /**
     * Método que busca un cliente por aproximación de su NIF
     * @param \Request $request
     * @return $ps
     */
    public function buscarCliente(Request $request){
        if ($request->nif) {
            $ps=DB::table('clientes')
            ->select('clientes.id','clientes.nombre', 'clientes.apellido1', 'clientes.apellido2', 'clientes.nif', 'direcciones.calle',
            'direcciones.numero', 'direcciones.km', 'direcciones.bloque', 'direcciones.piso', 'direcciones.letra',
            'clientes.user', 'clientes.password', 'clientes.bonificacion')
            ->leftJoin('direcciones', 'direcciones.id', '=','clientes.direccione_id')
            ->where('nif', 'like', '%' . $request->nif . '%')->get();
        }
        if ($request->nombre) {
            $ps=DB::table('clientes')
            ->select('clientes.id', 'clientes.nombre', 'clientes.apellido1', 'clientes.apellido2', 'clientes.nif', 'direcciones.calle',
            'direcciones.numero', 'direcciones.km', 'direcciones.bloque', 'direcciones.piso', 'direcciones.letra',
            'clientes.user', 'clientes.password', 'clientes.bonificacion')
            ->leftJoin('direcciones', 'direcciones.id', '=','clientes.direccione_id')
            ->where('nombre', 'like', '%' . $request->nombre . '%')->get();
        }
        if ($request->apellido1) {
            $ps=DB::table('clientes')
            ->select('clientes.id', 'clientes.nombre', 'clientes.apellido1', 'clientes.apellido2', 'clientes.nif', 'direcciones.calle',
            'direcciones.numero', 'direcciones.km', 'direcciones.bloque', 'direcciones.piso', 'direcciones.letra',
            'clientes.user', 'clientes.password', 'clientes.bonificacion')
            ->leftJoin('direcciones', 'direcciones.id', '=','clientes.direccione_id')
            ->where('apellido1', 'like', '%' . $request->apellido1 . '%')->get();
        }
        if ($request->apellido2) {
            $ps=DB::table('clientes')
            ->select('clientes.id','clientes.nombre', 'clientes.apellido1', 'clientes.apellido2', 'clientes.nif', 'direcciones.calle',
            'direcciones.numero', 'direcciones.km', 'direcciones.bloque', 'direcciones.piso', 'direcciones.letra',
            'clientes.user', 'clientes.password', 'clientes.bonificacion')
            ->leftJoin('direcciones', 'direcciones.id', '=','clientes.direccione_id')
            ->where('apellido2', 'like', '%' . $request->apellido2 . '%')->get();
        }
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "clientes"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "clientes"=>'',
                'registros'=>0
            ];
        }
        return $data;
    }

    /**
     * Verifica si existe o no el nif y/o el nombre de usuario
     * @param Request $request
     */
    public static function nifUser(Request $request){
        if($request->nif){
            $data=Cliente::where('nif', '=', $request->nif);
        }
        if($request->user){
            $data=Cliente::where('user', '=', $request->user);
        }
        if($data->count()>0){
            $datos=[
                "mensaje"=>"Ya existe un cliente registrado en la base de datos.",
                "status"=>true
            ];
            return $datos;
        }else{
            $datos=[
                "mensaje"=>"No se ha encontrado ningun registro.",
                "status"=>false
            ];
            return $datos;
        }
    }
}
