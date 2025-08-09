<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Maquina;
use App\Models\Produccion;

class Tarea extends Model
{
    protected $fillable = [
        'id_maquina', 
        'id_produccion',
        'fecha_hora_inicio',
        'fecha_hora_termino',
        'tiempo_empleado',
        'tiempo_produccion',
        'estado',
    ];

    public function maquina() 
    {
        return $this->belongsTo(Maquina::class, 'id_maquina');
    }

    public function produccion()
    {
        return $this->belongsTo(Produccion::class, 'id_produccion');
    }
}
