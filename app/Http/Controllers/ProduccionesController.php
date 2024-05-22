<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;
use App\Models\Piscina;
use App\Models\Balanceado;
use App\Models\Produccion;

class ProduccionesController extends Controller
{
    
    protected $grupo = "producciones";
    protected $modulo = "Producciones";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Producción";
        $camaroneras = Camaronera::get();
        $pisinas = Piscina::get();
        $producciones = Produccion::get();
        return view('produccion.index', compact('grupo', 'modulo', 'vista', 'camaroneras', 'pisinas', 'producciones'));
    }
    
    public function camaronera(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $camaronera = Camaronera::find($id);
        if(!$camaronera){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $vista = "Piscinas: " . $camaronera->nombre;
        $pisinas = Piscina::get();
        $piscinasId = $pisinas->pluck('id');
        $producciones = Produccion::whereIn('id_piscina', $piscinasId)->get();
        return view('produccion.camaroneras', compact('grupo', 'modulo', 'vista', 'camaronera', 'pisinas', 'producciones'));
    }
    
    public function piscina(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $piscina = Piscina::find($id);
        if(!$piscina){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $vista = "Producciones: " . $piscina->camaronera->nombre . " - Piscina: " . $piscina->num . ' - ' . $piscina->nombre;
        $producciones = Produccion::where('id_piscina', $piscina->id)->get();
        return view('produccion.piscinas', compact('grupo', 'modulo', 'vista', 'piscina', 'producciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $piscina = Piscina::find($id);
        if(!$piscina){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $vista = "Nueva Producción: " . $piscina->camaronera->nombre . " - Piscina: " . $piscina->num . ' - ' . $piscina->nombre;

        return view('produccion.create', compact('grupo', 'modulo', 'piscina', 'vista'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $piscina = Piscina::find($id);
        if(!$piscina){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $data = $request->validate([
            'fecha' => 'required|date',
            'densidad' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'dias_ciclo' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'peso_transferencia' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'costo_larva' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'multiplo_redondeo' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'supervivencia_30' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'supervivencia_fin' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'capacidad_carga' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'costo_fijo' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
        ]);
        $data['id_piscina'] = $piscina->id;
        $produccion_activa = Produccion::where('id_piscina', $piscina->id)
            ->where('estado', 1)
            ->first();

        if ($produccion_activa) {
            $data['estado'] = 0;
        } else {
            $data['estado'] = 1;
        }
        $producción = Produccion::create($data);
        return redirect('producciones/piscina/'.$piscina->id)->with('success', 'Producción Creada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $produccion = Produccion::find($id);
        if(!$produccion){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $vista = "Consultar Producción " . $produccion->fecha . " - " . $produccion->dias_ciclo . " días";
        return view('produccion.show', compact('grupo', 'modulo', 'vista', 'produccion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $produccion = Produccion::find($id);
        if(!$produccion){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $vista = "Actualizar Producción " . $produccion->fecha;
        return view('produccion.edit', compact('grupo', 'modulo', 'vista', 'produccion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produccion = Produccion::find($id);
        if(!$produccion){
            return redirect('producciones')->withErrors('El Registro No Existe');
        }
        $data = $request->validate([
            'fecha' => 'required|date',
            'densidad' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'dias_ciclo' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'peso_transferencia' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'costo_larva' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'multiplo_redondeo' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'supervivencia_30' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'supervivencia_fin' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'capacidad_carga' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'costo_fijo' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
        ]);
        $produccion_activa = Produccion::where('id_piscina', $produccion->id_piscina)
            ->where('estado', 1)
            ->whereNot('id', $produccion->id)
            ->first();

        if ($produccion_activa) {
            echo $data['estado'] = 0;
        } else {
            echo $data['estado'] = 1;
        }
        $produccion->update($data);
        return redirect('producciones/'.$produccion->id)->with('success', 'Producción Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produccion = Produccion::find($id);
        if(!$produccion){
            return back()->withErrors('La Producción No Existe');
        }

        try {
            $produccion->delete();
            return redirect('producciones/piscina/'.$produccion->id_piscina)->with('success', 'Producción eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('producciones/piscina/'.$produccion->id_piscina)->withErrors('No se puede eliminar, mantiene registros relacionados.');
        }
    }
}
