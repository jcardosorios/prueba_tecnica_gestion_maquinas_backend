<?php

namespace App\Http\Controllers;

use App\Rules\ValidarHorasEmpleo;
use App\Models\Tarea;
use App\Models\Maquina;
use App\Models\Produccion;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Tarea::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $horasEmpleoValidas = new ValidarHorasEmpleo;
        
        // Validación
        $validatedData = $request->validate([
            'id_maquina' => 'required|exists:maquinas,id',
            'fecha_hora_inicio' => 'required|date',
            'fecha_hora_termino' => [
                'nullable',
                'date',
                'after_or_equal:fecha_hora_inicio',
                'before:now',
                $horasEmpleoValidas
            ],
        ]);

        // Creacion de datos iniciales de la tarea
        $data = [
            'id_maquina' => $validatedData['id_maquina'],
            'fecha_hora_inicio' => $validatedData['fecha_hora_inicio'],
            'fecha_hora_termino' => $validatedData['fecha_hora_termino'] ?? null,
            'estado' => 'PENDIENTE',
            'tiempo_empleado' => null,
            'tiempo_produccion' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];


        // Verificacion y modificacion en caso de existir hora de termino
        if(isset($validatedData['fecha_hora_termino']))
        {
            // Calculo de tiemplo_empleado y tiempo_produccion
            $tiempo_empleado = $horasEmpleoValidas->getTiempoEmpleado();
            $maquina = Maquina::findOrFail($validatedData['id_maquina']);
            $tiempo_produccion = $tiempo_empleado * $maquina->coeficiente;

            $data['tiempo_empleado'] = $tiempo_empleado;
            $data['tiempo_produccion'] = $tiempo_produccion;
            $data['estado'] = 'COMPLETADA';
        }

        // Creación de tarea
        $tarea = Tarea::create($data);
        return response()->json($tarea, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarea $tarea)
    {
        return $tarea;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarea $tarea)
    {
        $horasEmpleoValidas = new ValidarHorasEmpleo;
        // Validaciones
        if ($tarea->estado !== 'PENDIENTE') {
            return response()->json(['error' => 'La tarea ya ha sido completada'], 409);
        }

        // Obtencion de hora de inicio para usar regla de validacion de horas de empleo
        $request->merge(['fecha_hora_inicio' => $tarea->fecha_hora_inicio]);

        $validatedData = $request->validate([
            'fecha_hora_termino' => [
                'required',
                'date',
                'after_or_equal:fecha_hora_inicio',
                'before:now',
                $horasEmpleoValidas,
            ]
        ]);

        // Calculo de tiemplo_empleado y tiempo_produccion
        $tiempo_empleado = $horasEmpleoValidas->getTiempoEmpleado();
        $maquina = Maquina::findOrFail($tarea->id_maquina);
        $tiempo_produccion = $tiempo_empleado * $maquina->coeficiente;

        $tarea->update([
            'fecha_hora_termino' => $validatedData['fecha_hora_termino'],
            'tiempo_empleado' => $tiempo_empleado,
            'tiempo_produccion' => $tiempo_produccion,
            'estado' => 'COMPLETADA',
        ]);

        return response()->json($tarea, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
