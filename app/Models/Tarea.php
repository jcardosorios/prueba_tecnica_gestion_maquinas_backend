<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Maquina;
use App\Models\Produccion;

class Tarea extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_termino' => 'datetime',
        'tiempo_empleado' => 'float',
        'tiempo_produccion' => 'float',
        'id_maquina' => 'integer', 
        'id_produccion' => 'integer',
    ];
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
