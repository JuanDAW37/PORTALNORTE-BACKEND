<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Iva extends Model
{
    use HasFactory;

    /**
     * Relación 1 a N con actividades (1 Iva se asocia a N actividades)
     */
    public function actividades():HasMany
    {
        return $this->hasMany(Actividade::class);
    }
}
