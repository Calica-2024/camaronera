<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;

class UsuarioCamaroneraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function asignarCamaronera($cam, $user)
    {
        $camaronera = Camaronera::find($cam);
        if(!$camaronera){
            return redirect('camaroneras')->withErrors('La Camaronera No Existe');
        }
        $usuario = User::find($user);
        if(!$usuario){
            return redirect('camaroneras')->withErrors('El Usuario No Existe');
        }

        $userCamaronera = UserCamaronera::where('id_user', $usuario->id)->where('id_camaronera', $camaronera->id)->first();
        if($userCamaronera){
            return redirect('camaroneras/'.$cam)->withErrors('El Usuario Ya Fue Asignado A Esta Camaronera');
        }
        $data['id_user'] = $usuario->id;
        $data['id_camaronera'] = $camaronera->id;
        UserCamaronera::create($data);
        return redirect('camaroneras/'.$cam)->with('success', 'Usuario Asignado Correctamente');
        
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
        $usuario = UserCamaronera::find($id);
        try {
            $usuario->delete();
            return redirect('camaroneras/'.$usuario->id_camaronera)->with('success', 'Usuario Eliminado Exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('camaroneras/'.$usuario->id_camaronera)->withErrors('No se puede eliminar, mantiene registros relacionados.');
        }
    }
}
