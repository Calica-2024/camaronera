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

            // Obtener los proyectos reales asociados a las producciones obtenidas y filtrarlos por la fecha
            $items = ProyectoReal::with('produccion.piscina')
                                    ->with('balanceado')
                                    ->whereIn('id_produccion', $producciones)
                                    ->whereDate('fecha', $fecha)
                                    ->get();

            // Añadir el peso promedio a cada item que coincide con id_produccion
            foreach ($items as $item) {
                $itemAnteriorData = $this->ultimoPeso($item->id_produccion, $item->num_dia);
                $item->inc3sem = $this->incProm3Sem($item->id_produccion, $item->num_dia) ?? 0;
                $item->peso_real = $item->peso_real ?? $itemAnteriorData['item'];
                $item->peso_real_anterior = $item->peso_real_anterior ?? $itemAnteriorData['itemAnterior'];
                $item->alimento = $item->alimento ?? $itemAnteriorData['alimento'];
                $item->densidad_consumo = $item->densidad_consumo ?? $itemAnteriorData['densidad_consumo'];
                $item->densidad_actual = $item->densidad_actual ?? $itemAnteriorData['densidad_actual'];
                $item->densidad_muestreo = $item->densidad_muestreo ?? $itemAnteriorData['densidad_muestreo'];
            }

            $proyectoItems = ProyectoCultivo::whereIn('id_produccion', $producciones)
                                            ->whereDate('fecha', $fecha)
                                            ->get();
                                    
            $itemAnteriores = [];
            
            //return count($proyectoItems);
        }

        return view('dashboard.dashboard', compact('grupo', 'modulo', 'camaronerasUser', 'items', 'itemAnteriores', 'proyectoItems', 'produccionesItems'));
    }

    public function ultimoPeso($produccionId, $dia){
        $item = ProyectoReal::where('id_produccion', $produccionId)
                                ->whereBetween('num_dia', [0, $dia])
                                ->whereNotNull('peso_real')
                                ->orderBy('num_dia', 'DESC')
                                ->first();
        return [
            'item' => $item ? $item->peso_real : null,
            'itemAnterior' => $item ? $item->peso_real_anterior : null,
            'alimento' => $item ? $item->alimento : null,
            'densidad_consumo' => $item ? $item->densidad_consumo : null,
            'densidad_actual' => $item ? $item->densidad_actual : null,
            'densidad_muestreo' => $item ? $item->densidad_muestreo : null,
        ];
    }
    
    public function incProm3Sem($produccionId, $dia){
        $promedioPeso3Sem = ProyectoReal::where('id_produccion', $produccionId)
            ->where('dia', 'domingo')
            ->whereBetween('num_dia', [0, $dia])
            ->orderBy('num_dia', 'DESC')
            ->take(3)
            ->avg('peso_real_anterior');
        return $promedioPeso3Sem;
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

    public function alimentoPiscina($id)
    {
        // Recuperar los datos filtrados y ordenados
        $produccionItems = ProyectoReal::where('id_produccion', $id)
            ->whereDate('Fecha', '<=', Carbon::now())
            ->orderBy('num_dia', 'asc')
            ->get(['alimento', 'fecha']); // Seleccionar solo los campos necesarios

        // Transformar los datos para devolver solo los campos deseados
        $resultados = $produccionItems->map(function ($item) {
            return [
                'alimento' => $item->alimento ?? 0, // Reemplaza null o valores ausentes por 0
                'fecha' => $item->fecha,
            ];
        });
    
        // Devuelve los datos en formato JSON
        return response()->json($resultados);
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
