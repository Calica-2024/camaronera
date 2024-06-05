<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\TablaAlimentacion;

class TablaAlimentacionController extends Controller
{
    
    protected $grupo = "ajustes";
    protected $modulo = "ta";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Tabla Alimentación";
        $items = TablaAlimentacion::get();
        return view('tablaAlim.index', compact('grupo', 'modulo', 'vista', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Crear Tabla Alimentación";
        return view('tablaAlim.create', compact('grupo', 'modulo', 'vista'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pesos' => 'required|numeric',
            'ta1' => 'required|numeric',
            'ta2' => 'required|numeric',
            'ta3' => 'required|numeric',
            'ta4' => 'required|numeric',
            'ta5' => 'required|numeric',
        ]);
        $data['pesos'] = $request->input('pesos');
        $data['ta1'] = $request->input('ta1');

        $peso = $data['pesos'];
        if ($peso <= 6.5) {
            $ta2 = 19.3675630190815 - 3.60782704974002 * log(11.047690861407 * $peso);
        } else {
            $ta2 = 8.89347102450592 * pow($peso, -0.40564200758529);
        }
        $data['ta2'] = round($ta2, 2);
        $data['ta3'] = $request->input('ta3');
        $data['ta4'] = $request->input('ta4');
        $data['ta5'] = ($data['ta3']+$data['ta4'])/2;
        $tabla = TablaAlimentacion::create($data);
        return redirect('tabla_alimentacion/'.$tabla->id)->with('success', 'Tabla Creada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Consultar Tabla Alimentación";
        $tabla = TablaAlimentacion::find($id);
        if(!$tabla){
            return redirect('tabla_alimentacion')->withErrors('El Registro No Existe');
        }
        return view('tablaAlim.show', compact('grupo', 'modulo', 'vista', 'tabla'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Actualizar Tabla Alimentación";
        $tabla = TablaAlimentacion::find($id);
        if(!$tabla){
            return redirect('tabla_alimentacion')->withErrors('El Registro No Existe');
        }
        return view('tablaAlim.edit', compact('grupo', 'modulo', 'vista', 'tabla'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tabla = TablaAlimentacion::find($id);
        if(!$tabla){
            return redirect('tabla_alimentacion')->withErrors('El Registro No Existe');
        }
        $request->validate([
            'pesos' => 'required|numeric',
            'ta1' => 'required|numeric',
            'ta2' => 'required|numeric',
            'ta3' => 'required|numeric',
            'ta4' => 'required|numeric',
            'ta5' => 'required|numeric',
        ]);
        $data['pesos'] = $request->input('pesos');
        $data['ta1'] = $request->input('ta1');

        $peso = $data['pesos'];
        if ($peso <= 6.5) {
            $ta2 = 19.3675630190815 - 3.60782704974002 * log(11.047690861407 * $peso);
        } else {
            $ta2 = 8.89347102450592 * pow($peso, -0.40564200758529);
        }
        $data['ta2'] = round($ta2, 2);
        $data['ta3'] = $request->input('ta3');
        $data['ta4'] = $request->input('ta4');
        $data['ta5'] = ($data['ta3']+$data['ta4'])/2;
        
        $tabla->update($data);
        return redirect('tabla_alimentacion/'.$tabla->id)->with('success', 'Tabla Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tabla = TablaAlimentacion::find($id);
        if(!$tabla){
            return redirect('tabla_alimentacion')->with('errors', 'El Registro No Existe');
        }
        try {
            $tabla->delete();
            return redirect('tabla_alimentacion')->with('success', 'Tabla eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('tabla_alimentacion')->withErrors('No se puede eliminar, mantiene registros relacionados.');
        }
    }
}
