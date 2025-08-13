<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use App\Models\Tarea;
use App\Models\Produccion;
use App\Models\Maquina;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class GenerarProduccionDeTareas implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Busca equipos que tengan 5 tareas pendientes y tiene todas las fechas
        $idsMaquinasConTareas = Tarea::where('estado', 'PENDIENTE')
            ->whereNull('id_produccion')
            ->whereNotNull('fecha_hora_inicio')
            ->whereNotNull('fecha_hora_termino')
            ->groupBy('id_maquina')
            ->havingRaw('count(*) >= 5')
            ->pluck('id_maquina');

        Log::info('Resultado de la consulta a Tarea:', ['maquinaConTareas' => $idsMaquinasConTareas]);
        // Si no hay coincidencia, se retorna
        if ($idsMaquinasConTareas->isEmpty()) return;


        // Obtener tareas pendientes de maquina seleccionada
        $tareas = Tarea::where('estado', 'PENDIENTE')
            ->whereNull('id_produccion')
            ->whereNotNull('fecha_hora_inicio')
            ->whereNotNull('fecha_hora_termino')
            ->where('id_maquina', $idsMaquinasConTareas[0])
            ->get();
        
        $tiempoProduccionTotal = $tareas->sum('tiempo_produccion');

        $ultimaTarea = $tareas->sortByDesc('fecha_hora_termino')->first();
        $fechaHoraTerminoUltimaTarea = $ultimaTarea->fecha_hora_termino;

        // Crea elemento en tabla produccion
        $produccion = Produccion::create([
            'tiempo_produccion' => $tiempoProduccionTotal,
            'tiempo_inactividad' => 0,
            'fecha_hora_inicio_inactividad' => now(),
            'fecha_hora_termino_inactividad' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);


        // Actualiza tareas pendientes
        foreach($tareas as $tarea) {
            $inicio = Carbon::parse($tarea->fecha_hora_inicio);
            $termino = Carbon::parse($tarea->fecha_hora_termino);

            // Calculo de tiempo empleado
            $tiempoEmpleado = $inicio-> diffInMinutes($termino)/60;
            // dd($tarea->maquina);
            // Actualizacion de tarea
            $tarea->id_produccion = $produccion->id;
            $tarea->tiempo_empleado = $tiempoEmpleado;
            $tarea->tiempo_produccion = $tiempoEmpleado * $tarea->maquina->coeficiente;
            $tarea->estado = 'COMPLETADA';
            $tarea->save();
        }

    }
}
