<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    use HasFactory;
    /**
     * Relación 1 a N con trabajadores (1 empresa tiene N trabajadores)
    */
    public function trabajadores():HasMany{
        return $this->hasMany(Trabajadore::class);
    }

    /**
     * Relación 1 a con direcciones (1 empresa está en 1 dirección)
     */
    public function direccione():BelongsTo{
        return $this->belongsTo(Direccione::class);
    }   

}
