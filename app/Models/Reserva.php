<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Reserva extends Model
{
    use HasFactory;
    /**
     * Relación 1 a 1 a facturas (1 reserva genera 1 factura)
     */
    public function factura():HasOne
    {
        return $this->hasOne(Factura::class);
    }

    /**
     * Relación 1 a N inversa con clientes (N facturas pertenecen a 1 cliente)
     */
    public function cliente():BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación 1 a N inversa con actividades (1 reserva puede tener varias actividades)
    */
    public function actividade():BelongsTo
    {
        return $this->belongsTo(Actividade::class);
    }

    /**
     * Método que realiza una búsqueda de la reserva aproximando por su número
     */
    public function buscar(Request $request){
        $ps=DB::table('reservas')
        ->select('reservas.id as id','reservas.numero as numero','reservas.facturada as facturada' ,'reservas.fecha as fecha',
        'reservas.hora as hora', 'reservas.personas as personas', 'actividades.actividad', 'clientes.nombre as nombre',
        'clientes.apellido1 as apellido1','clientes.apellido2 as apellido2', 'clientes.nif as nif')
        ->leftJoin('actividades', 'actividades.id', '=', 'reservas.actividade_id')
        ->leftJoin('clientes', 'clientes.id', '=', 'reservas.cliente_id')
        ->leftJoin('direcciones', 'direcciones.id', '=', 'clientes.direccione_id')
        ->where('reservas.numero', 'like', '%' . $request->numero . '%')->get();
        $data=[];
        if($ps->count()>0){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "reservas"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "reservas"=>'',
                'registros'=>0
            ];
        }
        return $data;


    }

    /**
     * Consulta para comprobar si el número de personas supera el límite por actividad, intervarlo de tiempo y fecha.
    */
    public static function getReservas(Request $request){
        $reservas=Reserva::where('actividade_id','1')->where('fecha',$request->fecha)->whereBetween('hora', [$request->inicio,$request->fin])->count();
        $personas=Reserva::where('actividade_id','1')->where('fecha',$request->fecha)->whereBetween('hora', [$request->inicio,$request->fin])->sum('personas');
        $data=[
            'mensaje'=>'Hay datos',
            'status'=>true,
            "reservas"=>$reservas,
            "personas"=>$personas
        ];
        return $data;
    }
}
