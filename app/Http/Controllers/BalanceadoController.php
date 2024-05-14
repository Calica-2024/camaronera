<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;
use App\Models\Piscina;
use App\Models\Balanceado;

class BalanceadoController extends Controller
{
    
    protected $grupo = "balanceados";
    protected $modulo = "Balanceados";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Balanceados";
        $balanceados = Balanceado::get();
        return view('balanceado.index', compact('grupo', 'modulo', 'vista', 'balanceados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Crear Balanceado";
        return view('balanceado.create', compact('grupo', 'modulo', 'vista'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|min:3|max:25',
            'tipo' => 'required|min:3|max:25',
            'marca' => 'required|min:3|max:25',
            'etapa' => 'required|min:3|max:50',
            'nomenclatura' => 'required|min:3|max:50',
            'unidad_medida' => 'required|min:1|max:15',
            'presentacion' => 'required|numeric',
            'precio' => 'required|numeric',
        ]);
        $data['estado'] = 1;
        $balanceado = Balanceado::create($data);
        return redirect('balanceados/'.$balanceado->id)->with('success', 'Balanceado Creado Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Consultar Balanceado";
        $balanceado = Balanceado::find($id);
        if(!$balanceado){
            return redirect('balanceados')->with('errors', 'El Balanceado No Existe');
        }
        return view('balanceado.show', compact('grupo', 'modulo', 'vista', 'balanceado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Actualizar Balanceado";
        $balanceado = Balanceado::find($id);
        if(!$balanceado){
            return redirect('balanceados')->with('errors', 'El Balanceado No Existe');
        }
        return view('balanceado.edit', compact('grupo', 'modulo', 'vista', 'balanceado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $balanceado = Balanceado::find($id);
        if(!$balanceado){
            return redirect('balanceados')->with('errors', 'El Blanceado No Existe');
        }
        $data = $request->validate([
            'nombre' => 'required|min:3|max:25',
            'tipo' => 'required|min:3|max:25',
            'marca' => 'required|min:3|max:25',
            'etapa' => 'required|min:3|max:50',
            'nomenclatura' => 'required|min:3|max:50',
            'unidad_medida' => 'required|min:1|max:15',
            'presentacion' => 'required|numeric',
            'precio' => 'required|numeric',
        ]);
        $data['estado'] = $request->has('estado') ? 1 : 0;
        $balanceado->update($data);
        return redirect('balanceados/'.$balanceado->id)->with('success', 'Blanceado Actualizado Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $balanceado = Balanceado::find($id);
        if(!$balanceado){
            return redirect('balanceados')->with('errors', 'El Blanceado No Existe');
        }
        try {
            $balanceado->delete();
            return redirect('balanceados')->with('success', 'Balanceado eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('balanceados')->withErrors('No se puede eliminar, mantiene registros relacionados.');
        }
    }
}
