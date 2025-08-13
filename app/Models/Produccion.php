<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $casts = [
        'tiempo_produccion' => 'float',
        'tiempo_inactividad' => 'float',
        'fecha_hora_inicio_inactividad' => 'datetime', 
        'fecha_hora_termino_inactividad' => 'datetime',
    ];

    protected $fillable = [
        'tiempo_produccion',
        'tiempo_inactividad',
        'fecha_hora_inicio_inactividad',
        'fecha_hora_termino_inactividad',
    ];
}
