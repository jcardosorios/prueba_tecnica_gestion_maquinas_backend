<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'coeficiente' => 'float',
    ];
    
    use HasFactory;

    protected $fillable = [
        'nombre',
        'coeficiente',
    ];
}
