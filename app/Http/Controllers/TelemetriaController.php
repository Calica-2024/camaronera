<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyectoReal;
use App\Models\Telemetria;

class TelemetriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cultivo = $request->id_p_real;
        $proyecto = ProyectoReal::find($cultivo);
        $data = $request->validate([
            'hora1' => 'nullable|time',
            'temperatura1' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'oxigeno1' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'hora2' => 'nullable|time',
            'temperatura2' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'oxigeno2' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'hora3' => 'nullable|time',
            'temperatura3' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'oxigeno3' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ]);

        Telemetria::updateOrCreate(
            ['id_p_real' => $cultivo], // Condición para encontrar el registro
            $data // Datos para actualizar o crear el registro
        );
        return redirect('producciones/'.$proyecto->id_produccion)->with('success', "Telemetria actualizada");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
