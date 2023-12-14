<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Actividade extends Model
{
    use HasFactory;

    //Para almacenamiento masivo
    protected $guarded=[];

    /**
     * Relación N a M con ubicaciones (se usan tablas pivote)
    */
    public function ubicaciones(){
        return $this->belongsToMany(Ubicacione::class);
    }

    /**
     * Relación N a M con materialess (se usan tablas pivote)
    */
    public function materiales():BelongsToMany{
        return $this->belongsToMany(Materiale::class);
    }

    /**
     * Relación N a M con trabajadores (se usan tablas pivote)
    */
    public function trabajadores():BelongsToMany{
        return $this->belongsToMany(Trabajadore::class);
    }

    /**Relación 1 a muchos inversa con Gestor */
    public function gestor():BelongsTo{
        return $this->belongsTo(Gestor::class);
    }

    /**Relación 1 a muchos inversa con IVA */
    public function iva():BelongsTo{
        return $this->belongsTo(Iva::class);
    }

    /**Relación 1 a muchos inversa con Tipos de actividades */
    public function tiposactividade():BelongsTo{
        return $this->belongsTo(Tiposactividade::class);
    }

    /**
     * Relación 1 a 1 con reservas
     */
    public function reservas():HasMany{
        return $this->hasMany(Reserva::class);
    }

    /**
     * Método para localizar actividades cuya tarifa y/o personas y/o duración,
     * estén comprendidas entre unos valores a la solicitados
     * @param $tarifa
     * @param $personas
     * @param $duracion
     * @return $data
     */
    public function consultar($tarifa, $personas, $duracion){
        if($tarifa>1){
            $ps=DB::table('actividades')
            ->select('actividades.id', 'actividades.actividad', 'actividades.tarifa', 'actividades.personas','actividades.duracion',
            'actividades.descripcion', 'ivas.nombre as nombreIva', 'ivas.tipo as tipoIva', 'tiposactividades.tipo as tipo')
            ->leftJoin('ivas', 'ivas.id', '=', 'actividades.iva_id')
            ->leftJoin('tiposactividades', 'tiposactividades.id', '=', 'actividades.tiposactividade_id')
            ->whereBetween('tarifa',[1,$tarifa])->get();
        }
        if($personas>1){
            $ps=DB::table('actividades')
            ->select('actividades.id', 'actividades.actividad', 'actividades.tarifa', 'actividades.personas','actividades.duracion',
            'actividades.descripcion', 'ivas.nombre as nombreIva', 'ivas.tipo as tipoIva', 'tiposactividades.tipo as tipo')
            ->leftJoin('ivas', 'ivas.id', '=', 'actividades.iva_id')
            ->leftJoin('tiposactividades', 'tiposactividades.id', '=', 'actividades.tiposactividade_id')
            ->whereBetween('personas', [1, $personas])->get();
        }
        if($duracion>1){
            $ps=DB::table('actividades')
            ->select('actividades.id', 'actividades.actividad', 'actividades.tarifa', 'actividades.personas','actividades.duracion',
            'actividades.descripcion', 'ivas.nombre as nombreIva', 'ivas.tipo as tipoIva', 'tiposactividades.tipo as tipo')
            ->leftJoin('ivas', 'ivas.id', '=', 'actividades.iva_id')
            ->leftJoin('tiposactividades', 'tiposactividades.id', '=', 'actividades.tiposactividade_id')
            ->whereBetween('duracion', [1, $duracion])->get();
        }
        $data=[];
        if(isset($ps)&&($ps->count()>0)){
            $data=[
                'mensaje'=>'Hay datos',
                'status'=>true,
                "actividades"=>$ps,
                "registros"=>$ps->count()
            ];
        }else{
            $data=[
                "mensaje"=> "No hay datos",
                "status"=>false,
                "actividades"=>$tarifa,
                'registros'=>0
            ];
        }
        return $data;
    }
}

