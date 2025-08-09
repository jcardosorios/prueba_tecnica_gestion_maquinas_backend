<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Illuminate\Http\Request;

class MaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Maquina::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validacion
        $validateData = $request->validate([
            'nombre' => 'required|string|max:255',
            'coeficiente' => 'required|numeric|min:1|max:3'
        ]);

        $maquina = Maquina::create($validateData);

        // Creacion
        return response()->json($maquina, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Maquina $maquina)
    {
        return $maquina;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maquina $maquina)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'coeficiente' => 'required|numeric|min:1|max:3',
        ]);

        $maquina->update($validatedData);
        return response()->json($maquina, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maquina $maquina)
    {
        $maquina->delete();
        return response()->json(null, 204);
    }
}
