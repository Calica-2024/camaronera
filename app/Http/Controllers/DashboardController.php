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
use Carbon\Carbon;

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
        $proyectoItems = [];
        $itemAnteriores = [];
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

            // Inicializar el arreglo para contar domingos por producción
            $domingosPorProduccion = [];

            // Contar domingos
            foreach ($producciones as $idProduccion) {
                // Obtener los 3 primeros domingos para esta producción
                $domingos = ProyectoReal::where('id_produccion', $idProduccion)
                    ->where('dia', 'domingo') // Filtrar por el campo 'dia'
                    ->whereDate('fecha', '<=', $fecha) // Asegurarse que la fecha sea menor o igual a la indicada
                    ->orderBy('fecha', 'desc') // Ordenar de forma descendente
                    ->take(3) // Limitar a los 3 primeros
                    ->get();
            
                // Guardar las fechas de los domingos encontrados
                $inc3sem = 0;
                if($domingos->count() > 0){
                    $inc3sem = $domingos->sum('peso_real_anterior') / $domingos->count();
                }
                $domingosPorProduccion[$idProduccion] = $inc3sem;
            }

            // Obtener los proyectos reales asociados a las producciones obtenidas y filtrarlos por la fecha
            $items = ProyectoReal::whereIn('id_produccion', $producciones)
                                    ->whereDate('fecha', $fecha)
                                    ->get();

            // Añadir el peso promedio a cada item que coincide con id_produccion
            foreach ($items as $item) {
                $item->inc3sem = $domingosPorProduccion[$item->id_produccion] ?? 0; // Agregar el promedio
            }

            $proyectoItems = ProyectoCultivo::whereIn('id_produccion', $producciones)
                                            ->whereDate('fecha', $fecha)
                                            ->get();
                                    
            $itemAnteriores = [];
            
            //return count($proyectoItems);
        }

        return view('dashboard.dashboard', compact('grupo', 'modulo', 'camaronerasUser', 'items', 'itemAnteriores', 'proyectoItems'));
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
