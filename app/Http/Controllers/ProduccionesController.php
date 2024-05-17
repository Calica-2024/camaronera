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
    public function store(Request $request)
    {
        //
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
