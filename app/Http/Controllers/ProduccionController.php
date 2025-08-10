<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Produccion::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Produccion $produccion)
    {
        return $produccion;
    }

}
