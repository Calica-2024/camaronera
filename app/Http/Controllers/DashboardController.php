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
use Barryvdh\DomPDF\Facade\Pdf; // Asegúrate de importar el facade


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
        $request->tabla;
        
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
    
            // Construir la consulta de producciones en estado 1, agrupadas por id_piscina, y seleccionando la producción con el mayor id por piscina
            $produccionesQuery = Produccion::whereIn('id_piscina', $piscinas)
                                            ->where('estado', 1)
                                            ->orderBy('id', 'desc')
                                            ->groupBy('id_piscina')
                                            ->selectRaw('max(id) as id');
                                            
            // Aplicar el filtro de 'tabla_alimentacion' si el valor de 'tabla' está presente en la solicitud
            if ($request->tabla) {
                $produccionesQuery->where('tabla_alimentacion', $request->tabla);
            }
    
            // Obtener los IDs de las producciones
            $producciones = $produccionesQuery->pluck('id');

            $produccionesItems = Produccion::whereIn('id_piscina', $piscinas)
                                            ->where('estado', 1)
                                            ->get();

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
            $items = ProyectoReal::with('produccion.piscina')
                                    ->with('balanceado')
                                    ->whereIn('id_produccion', $producciones)
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

        return view('dashboard.dashboard', compact('grupo', 'modulo', 'camaronerasUser', 'items', 'itemAnteriores', 'proyectoItems', 'produccionesItems'));
    }
    
    public function resumen(Request $request)
    {
        // Decodificar los arrays JSON recibidos
        $items = json_decode($request->input('items'), true);
        $itemAnteriores = json_decode($request->input('itemAnteriores'), true);
        $proyectoItems = json_decode($request->input('proyectoItems'), true);
        $produccionesItems = json_decode($request->input('produccionesItems'), true);

        // Generar el PDF
        $pdf = Pdf::loadView('dashboard.resumen', compact('items', 'itemAnteriores', 'proyectoItems', 'produccionesItems'))->setPaper('a4', 'landscape');

        // Descargar el PDF
        return $pdf->download('resumen.pdf');
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
    public function metricasProd($id)
    {
        $proyectoItems = ProyectoCultivo::where('id_produccion', $id)->orderBy('num_dia', 'asc')->get();
        $produccionItems = ProyectoReal::where('id_produccion', $id)->whereDate('Fecha', '<=', Carbon::now())->orderBy('num_dia', 'asc')->get();

        // Devuelve los datos en formato JSON
        return response()->json([
            'proyectoItems' => $proyectoItems,
            'produccionItems' => $produccionItems
        ]);
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
