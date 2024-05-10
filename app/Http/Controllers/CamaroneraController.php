<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;
use App\Models\Piscina;

class CamaroneraController extends Controller
{
    
    protected $grupo = "camaroneras";
    protected $modulo = "Camaroneras";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Camaroneras";
        $camaroneras = Camaronera::get();
        return view('camaroneras.index', compact('grupo', 'modulo', 'vista', 'camaroneras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Crear Camaronera";
        return view('camaroneras.create', compact('grupo', 'modulo', 'vista'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|min:3|max:25',
            'direccion' => 'required|min:3|max:100',
        ]);
        $data['estado'] = 1;
        $camaronera = Camaronera::create($data);
        return redirect('camaroneras/'.$camaronera->id)->with('success', 'Camaronera Creada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Consultar Camaronera";
        $camaronera = Camaronera::find($id);
        if(!$camaronera){
            return redirect('camaroneras')->with('errors', 'La Camaronera No Existe');
        }
        $usuarios = UserCamaronera::where('id_camaronera', $camaronera->id)->get();
        $idUsers = $usuarios->pluck('id_user');
        $users = User::whereNotIn('id', $idUsers)->get();
        $piscinas = Piscina::where('id_camaronera', $camaronera->id)->get();
        return view('camaroneras.show', compact('grupo', 'modulo', 'vista', 'camaronera', 'users', 'usuarios', 'piscinas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Actualizar Camaronera";
        $camaronera = Camaronera::find($id);
        if(!$camaronera){
            return redirect('camaroneras')->with('errors', 'La Camaronera No Existe');
        }
        return view('camaroneras.edit', compact('grupo', 'modulo', 'vista', 'camaronera'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $camaronera = Camaronera::find($id);
        if(!$camaronera){
            return redirect('camaroneras')->with('errors', 'La Camaronera No Existe');
        }
        $data = $request->validate([
            'nombre' => 'required|min:3|max:25',
            'direccion' => 'required|min:3|max:100',
        ]);
        $data['estado'] = $request->has('estado') ? 1 : 0;
        $camaronera->update($data);
        return redirect('camaroneras/'.$camaronera->id)->with('success', 'Camaronera Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $camaronera = Camaronera::find($id);
        try {
            $camaronera->delete();
            return redirect('camaroneras')->with('success', 'Camaronera eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('camaroneras')->withErrors('No se puede eliminar, mantiene registros relacionados.');
        }
    }
}
