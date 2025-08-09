<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $fillable = [
        'tiempo_produccion',
        'tiempo_inactividad',
        'fecha_hora_inicio_inactividad',
        'fecha_hora_termino_inactividad',
    ];
}
