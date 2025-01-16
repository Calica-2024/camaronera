<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCamaronera;
use App\Models\Piscina;
use App\Models\ProyectoReal;
use App\Models\ProyectoCultivo;
use App\Models\Produccion;

class PescasController extends Controller
{
    
    protected $grupo = "pescas";
    protected $modulo = "Pescas";
    protected $meses = [
        ['id' => 1, 'nombre' => 'Enero'],
        ['id' => 2, 'nombre' => 'Febrero'],
        ['id' => 3, 'nombre' => 'Marzo'],
        ['id' => 4, 'nombre' => 'Abril'],
        ['id' => 5, 'nombre' => 'Mayo'],
        ['id' => 6, 'nombre' => 'Junio'],
        ['id' => 7, 'nombre' => 'Julio'],
        ['id' => 8, 'nombre' => 'Agosto'],
        ['id' => 9, 'nombre' => 'Septiembre'],
        ['id' => 10, 'nombre' => 'Octubre'],
        ['id' => 11, 'nombre' => 'Noviembre'],
        ['id' => 12, 'nombre' => 'Diciembre'],
    ];    
    protected $anios = [
        '2023', '2024', '2025', '2026',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $grupo = $this->grupo;
        $modulo = $this->modulo;
         
        $items = [];
        $proyectoItems = [];
        $camaroneras = UserCamaronera::with('camaronera')->where('id_user', auth()->id())->get();

        $request->camaronera = $request->camaronera ?: $camaroneras->first()->camaronera->id;
        $request->mes = $request->mes ?? date('m');
        $request->anio = $request->anio ?? date('Y');
        $request->piscina;
        
        $piscinas = $request->camaronera ? Piscina::where('id_camaronera', $request->camaronera)->get() : [];
        $meses = $this->meses;
        $anios = $this->anios;

        $itemsProd = ProyectoReal::whereYear('fecha', $request->anio)
                                    ->whereMonth('fecha', $request->mes)
                                    ->get();
        
        $producciones = Produccion::whereIn('id', $itemsProd->pluck('id_produccion'))
                                    ->whereHas('piscina', function ($query) use ($request) {
                                        $query->where('id_camaronera', $request->camaronera);
                                    })
                                    ->when($request->filled('piscina'), function ($query) use ($request) {
                                        $query->where('id_piscina', $request->piscina);
                                    })
                                    ->get();
        $producciones = $producciones->map(function ($produccion) {
            // Calcular la fecha de fin sumando los días de ciclo
            $produccion->fecha_fin = \Carbon\Carbon::parse($produccion->fecha)
                ->addDays($produccion->dias_ciclo);
        
            return $produccion;
        });
        
        $producciones = $producciones->filter(function ($produccion) {
            // Comprobar si la fecha de finalización es mayor que la fecha actual
            return $produccion->fecha_fin->isPast(); // Si la fecha de finalización ya pasó
        });

        foreach ($producciones as $produccion) {
            $ultimoProyecto = ProyectoReal::whereYear('fecha', $request->anio)
                                            ->whereMonth('fecha', $request->mes)
                                            ->where('id_produccion', $produccion->id)
                                            ->orderBy('num_dia', 'desc')
                                            ->first();
            $ultimoProyecto->raleada = $ultimoProyecto->biomasa_raleada;
            $ultimoProyectoCul = ProyectoCultivo::whereYear('fecha', $request->anio)
                                            ->whereMonth('fecha', $request->mes)
                                            ->where('id_produccion', $produccion->id)
                                            ->orderBy('num_dia', 'desc')
                                            ->first();
            $items[] = $ultimoProyecto;
            $proyectoItems[] = $ultimoProyectoCul;
        }
        $proyectoItems = collect($proyectoItems);

        return view('pescas.index', compact('grupo', 'modulo', 'camaroneras', 'piscinas', 'meses', 'anios', 'items', 'proyectoItems', 'request'));
    }
}
