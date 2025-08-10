<?php

namespace App\Rules;

use Closure;
use App\Models\Tarea;
use Illuminate\Contracts\Validation\ValidationRule;

class TareaAnteriorFinalizada implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Contar tareas pendientes de una maquina especifica
        $ultimaTarea = Tarea::where('id_maquina', $value)
                            ->latest('created_at')
                            ->first();
        
        if ($ultimaTarea && is_null($ultimaTarea->fecha_hora_termino)) {
            $fail('No se puede generar nueva tarea en una maquina sin haber terminado la anterior');
        }
    }
}
