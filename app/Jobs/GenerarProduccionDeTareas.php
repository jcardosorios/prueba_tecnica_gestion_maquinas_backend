<?php

namespace App\Jobs;

use App\Utils\DateHelper;
use App\Models\Tarea;
use App\Models\Produccion;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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

        // Log::info('IDs de máquinas con 5 o más tareas encontradas:', ['ids' => $idsMaquinasConTareas->toArray()]);

        // Si no hay coincidencia, se retorna
        if ($idsMaquinasConTareas->isEmpty()) return;

        // Obtener tareas pendientes de maquina seleccionada
        $tareas = Tarea::where('estado', 'PENDIENTE')
            ->whereNull('id_produccion')
            ->whereNotNull('fecha_hora_inicio')
            ->whereNotNull('fecha_hora_termino')
            ->where('id_maquina', $idsMaquinasConTareas[0])
            ->get();

        // Log::info('Tareas obtenidas para la primera máquina:', ['tareas' => $tareas->toArray()]);

        // Crea elemento en tabla produccion
        $produccion = Produccion::create([
            'tiempo_produccion' => 0,
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

            // Actualizacion de tarea
            $tarea->id_produccion = $produccion->id;
            $tarea->tiempo_empleado = $tiempoEmpleado;
            $tarea->tiempo_produccion = $tiempoEmpleado * $tarea->maquina->coeficiente;
            $tarea->estado = 'COMPLETADA';
            $tarea->save();
        }

        // Calculo tiempo inactividad
        $ultimaTarea = $tareas->sortByDesc('fecha_hora_termino')->first();
        
        // Siguiente dia habil con hora aleatoria entre 9 y 14
        $fechaHoraTerminoUltimaTarea = $ultimaTarea->fecha_hora_termino;
        $siguienteDiaHabil = DateHelper::getNextWorkingDay($fechaHoraTerminoUltimaTarea);
        $horaAleatoria = DateHelper::getRandomTime();
        
        $fechaInicioInactividad = $siguienteDiaHabil
            ->setTimezone('America/Santiago')
            ->setTime(
                $horaAleatoria->hour,
                $horaAleatoria->minute,
                $horaAleatoria->second
            );

        // Tiempo de inactividad maximo
        $tiempoInactividadMaximo = $tareas->sum('tiempo_produccion');

        $tiempoInactividadAcumulado = 0;
        $fechaControl = $fechaInicioInactividad->copy();
        $tiempoRestante = $tiempoInactividadMaximo;
        $fechaTerminoInactividad = null;
        
        do{
            $horasInactividadDia = 0;
            $penalizadorDiario = 0;
            
            $esMiercoles = $fechaControl->dayOfWeek === Carbon::WEDNESDAY;
            
            // Horas de inactividad bruta por dia
            if($tiempoInactividadAcumulado == 0) {
                $finalDelDia = $fechaControl->copy()->setTime(16,0);
                $horasInactividadDia = $fechaControl->diffInMinutes($finalDelDia)/60;
            } else {
                $horasInactividadDia = min(7, $tiempoRestante);
            }

            // Penalizadores
            if ($esMiercoles) {
                $penalizadorDiario = 2.5;
            } elseif($horasInactividadDia < 5) {
                $penalizadorDiario = 1.5;
            }

            // Si el tiempo restante es menor que el penalizador, este es cero
            if ($tiempoRestante <= $penalizadorDiario) {
                $penalizadorDiario = 0;
            }

            // En el caso que el primer dia de inactividad sea miercoles
            // y comience a las 14:00, al tener solo 2 horas de inactividad
            // y un penalizador de 2.5 horas, se considerará minimo 0 horas de inactividad
            $tiempoInactividadFinalDia = max($horasInactividadDia - $penalizadorDiario, 0);
            
            $tiempoInactividadAcumulado += $tiempoInactividadFinalDia;
            $tiempoRestante = $tiempoInactividadMaximo - $tiempoInactividadAcumulado;

            $fechaTerminoInactividad = $fechaControl->copy()->setTime(9,0)->addMinutes($tiempoInactividadFinalDia * 60);
            $fechaControl = DateHelper::getNextWorkingDay($fechaControl)->setTime(9, 0, 0);

        } while(($tiempoRestante > $penalizadorDiario) && ($tiempoInactividadAcumulado != $tiempoInactividadMaximo));
        
        // Crea elemento en tabla produccion
        $produccion->update([
            'tiempo_produccion' => $tiempoInactividadMaximo,
            'tiempo_inactividad' => $tiempoInactividadAcumulado,
            'fecha_hora_inicio_inactividad' => $fechaInicioInactividad,
            'fecha_hora_termino_inactividad' => $fechaTerminoInactividad,
            'updated_at' => now()
        ]);


    }
}
