<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\DateHelper;
use Carbon\Carbon;

function calcularFechaTerminoInactividad(Carbon $fechaInicioInactividad, float $tiempoInactividadMaximo): Carbon
{

    $tiempoInactividadAcumulado = 0;
    $fechaControl = $fechaInicioInactividad->copy();
    $tiempoRestante = $tiempoInactividadMaximo;
    $fechaTerminoInactividad = null;

    do {
        $horasInactividadDia = 0;
        $penalizadorDiario = 0;

        $esMiercoles = $fechaControl->dayOfWeek === \Carbon\Carbon::WEDNESDAY;

        if($tiempoInactividadAcumulado == 0) {
                $finalDelDia = $fechaControl->copy()->setTime(16,0);
                $horasInactividadDia = $fechaControl->diffInMinutes($finalDelDia)/60;
        } else {
            $horasInactividadDia = min(7, $tiempoRestante);
        }

        echo "Tiempo de inactividad bruto: ". $horasInactividadDia ."\n";

        if ($esMiercoles) {
            $penalizadorDiario = 2.5;
        } elseif($horasInactividadDia < 5) {
            $penalizadorDiario = 1.5;
        }

        if ($tiempoRestante <= $penalizadorDiario) {
            $penalizadorDiario = 0;
        }

        echo "Penalizador: ". $penalizadorDiario."\n";

        $tiempoInactividadFinalDia = max($horasInactividadDia - $penalizadorDiario, 0);
        echo "Tiempo de inactividad limpio: ". $tiempoInactividadFinalDia."\n"; 
        
        $tiempoInactividadAcumulado += $tiempoInactividadFinalDia;
        echo "Tiempo acumulado: ". $tiempoInactividadAcumulado."\n"; 
        $tiempoRestante = $tiempoInactividadMaximo - $tiempoInactividadAcumulado;
        echo "Tiempo restante: ". $tiempoRestante."\n"; 

        $fechaTerminoInactividad = $fechaControl->copy()->setTime(9,0)->addMinutes($tiempoInactividadFinalDia * 60);
        $fechaControl = DateHelper::getNextWorkingDay($fechaControl)->setTime(9, 0, 0);

        echo "\n";
    } while(($tiempoRestante > $penalizadorDiario) && ($tiempoInactividadAcumulado != $tiempoInactividadMaximo));

    return $fechaTerminoInactividad;
}

$fechaInicio1 = Carbon::create(2025, 3, 17, 11, 30, 0);
$tiempoMaximo1 = 29;
$resultado1 = calcularFechaTerminoInactividad($fechaInicio1, $tiempoMaximo1);
echo "Caso 1: Inicio el " . $fechaInicio1->format('Y-m-d H:i') . " con 25 horas de inactividad.\n";
echo "Resultado: " . $resultado1->format('Y-m-d H:i') . "\n\n";