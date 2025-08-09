<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class ValidarHorasEmpleo implements ValidationRule
{
    private $tiempoEmpleado = 0;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $fechaInicio = request()->input('fecha_hora_inicio');

        $inicio = Carbon::parse($fechaInicio);
        $termino = Carbon::parse($value);

        $this->tiempoEmpleado = $inicio-> diffInMinutes($termino)/60;

        if ($this->tiempoEmpleado < 5 || $this->tiempoEmpleado > 120){
            $fail('El tiempo empleado debe estar entre 5 y 120 horas');
        }
    }

    public function getTiempoEmpleado()
    {
        return $this->tiempoEmpleado;
    }
}
