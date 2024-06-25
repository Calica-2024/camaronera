<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;
use App\Models\Piscina;
use App\Models\Balanceado;
use App\Models\Produccion;
use App\Models\ProyectoCultivo;
use App\Models\TablaAlimentacion;
use App\Models\ProyectoReal;

class DashboardController extends Controller
{
    
    protected $grupo = "dashboard";
    protected $modulo = "Dashboard";
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;

        $request->camaronera;
        $request->fecha;
        
        $camaronerasUser = UserCamaronera::where('id_user', auth()->id())->get();
        $items = [];
        if($camaronerasUser->isNotEmpty()){
            // Verificar si el valor 'camaronera' está presente en la solicitud
            if ($request->camaronera) {
                $idCamaronera = $request->camaronera;
            } else {
                // Si no está presente, usar el primer id_camaronera del conjunto de camaroneras del usuario
                $idCamaronera = $camaronerasUser->first()->camaronera->id;
            }

            // Obtener las piscinas correspondientes al id_camaronera determinado
            $piscinas = Piscina::where('id_camaronera', $idCamaronera)->pluck('id');
            
            // Obtener las producciones en estado 1, agrupadas por id_piscina, y seleccionando la producción con el mayor id por piscina
            $producciones = Produccion::whereIn('id_piscina', $piscinas)
                                        ->where('estado', 1)
                                        ->orderBy('id', 'desc')
                                        ->groupBy('id_piscina')
                                        ->selectRaw('max(id) as id')
                                        ->pluck('id');

            // Verificar si el valor 'fecha' está presente en la solicitud
            if ($request->has('fecha')) {
                $fecha = $request->fecha;
            } else {
                // Si no está presente, usar la fecha actual
                $fecha = now()->toDateString();
            }

            // Obtener los proyectos reales asociados a las producciones obtenidas y filtrarlos por la fecha
            $items = ProyectoReal::whereIn('id_produccion', $producciones)
                                 ->whereDate('fecha', $fecha)
                                 ->get();
        }

        return view('dashboard.dashboard', compact('grupo', 'modulo', 'camaronerasUser', 'items'));
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
        //
    }
}
