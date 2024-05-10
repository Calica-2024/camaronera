<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;
use App\Models\Piscina;

class PiscinaController extends Controller
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
        $data = $request->validate([
            'numero' => 'required|numeric',
            'nombre' => 'required|min:3|max:15',
            'id_camaronera' => 'required',
        ]);
        // Convertir el nombre a mayúsculas antes de asignarlo a los datos
        $data['nombre'] = strtoupper($data['nombre']);
        // Agregar el estado
        $data['estado'] = 1;
        // Crear la piscina
        $camaronera = Piscina::create($data);
        
        return redirect('camaroneras/'.$request->input('id_camaronera'))->with('success', 'Piscina Creada Exitosamente');
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
        $piscina = Piscina::find($id);
        if(!$piscina){
            return back()->withErrors('La Piscina No Existe');
        }

        $data = $request->validate([
            'numero' => 'required|numeric',
            'nombre' => 'required|min:3|max:15',
        ]);
        $data['nombre'] = strtoupper($data['nombre']);
        $data['estado'] = 1;
        $piscina->update($data);

        return redirect('camaroneras/'.$piscina->id_camaronera)->with('success', 'Piscina Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $piscina = Piscina::find($id);
        if(!$piscina){
            return back()->withErrors('La Piscina No Existe');
        }

        try {
            $piscina->delete();
            return redirect('camaroneras/'.$piscina->id_camaronera)->with('success', 'Piscina eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('camaroneras')->withErrors('No se puede eliminar, mantiene registros relacionados.');
        }
    }
}
