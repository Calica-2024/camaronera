<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Camaronera;
use App\Models\User;
use App\Models\UserCamaronera;
use App\Models\Piscina;
use App\Models\Balanceado;
use App\Models\Produccion;
use App\Models\ProyectoCultivo;
use App\Models\TablaAlimentacion;
use App\Models\ProyectoReal;

class ProduccionesController extends Controller
{
    
    protected $grupo = "producciones";
    protected $modulo = "Producciones";
    protected $crecimientoPorDias1 = [
        ["min" => 0, "max" => 15, "crecimiento" => 0.35],
        ["min" => 15, "max" => 30, "crecimiento" => 0.45],
        ["min" => 30, "max" => 45, "crecimiento" => 0.47],
        ["min" => 45, "max" => 60, "crecimiento" => 0.47],
        ["min" => 60, "max" => PHP_INT_MAX, "crecimiento" => 0.44], // PHP_INT_MAX para manejar el caso "> 60"
    ];
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
        $pisinas = Piscina::where('id_camaronera', $camaronera->id)->get();
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
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],
            'dias_ciclo' => [
                'required',
                'numeric',
                'min:1'
            ],
            'peso_transferencia' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'costo_larva' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],
            'multiplo_redondeo' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],
            'supervivencia_30' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],
            'supervivencia_fin' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],
            'capacidad_carga' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],
            'costo_fijo' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1'
            ],

            'crecimiento1' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento2' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento3' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento4' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento5' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'tabla_alimentacion' => 'required|string',
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
        $produccion = Produccion::create($data);
        $this->crearProyecto($produccion->id);
        return redirect('producciones/'.$produccion->id)->with('success', 'Producción Creada Exitosamente');
    }

    public function crearProyecto($id){
        $produccion = Produccion::find($id);
        $balanceado = Balanceado::where('estado', 1)->first();
        $dias = $produccion->dias_ciclo;
        $fechaInicial = Carbon::parse($produccion->fecha);
        $data = [];
        $crecimientoProy = [
            ["min" => 0, "max" => 15, "crecimiento" => $produccion->crecimiento1],
            ["min" => 15, "max" => 30, "crecimiento" => $produccion->crecimiento2],
            ["min" => 30, "max" => 45, "crecimiento" => $produccion->crecimiento3],
            ["min" => 45, "max" => 60, "crecimiento" => $produccion->crecimiento4],
            ["min" => 60, "max" => PHP_INT_MAX, "crecimiento" => $produccion->crecimiento5], // PHP_INT_MAX para manejar el caso "> 60"
        ];
        $sumaDensRal = 0;
        for ($i = 0; $i < $dias; $i++) {
            $data = [];

            $data['num_dia'] = $i+1;
            $fechaProyecto = $fechaInicial->copy()->addDays($i);
            $diaSemana = $fechaProyecto->locale('es')->isoFormat('dddd');
            
            if($data['num_dia'] == 1){
                $data['peso_proyecto'] = $produccion->peso_transferencia;
                $data['crecimiento_lineal'] = ( $data['peso_proyecto'] - $produccion->peso_transferencia ) / $data['num_dia'];
                $data['supervivencia_base'] = 100;
                $data['densidad_raleada'] = 0;
                $data['densidad'] = ( ( $data['supervivencia_base'] * $produccion->densidad )/100 ) - $data['densidad_raleada']; //0 representa densidad raleada
                $data['biomasa_raleada'] = $data['densidad_raleada'] * $data['peso_proyecto'] * 22;
                $data['biomasa'] = $data['densidad'] * $data['peso_proyecto'] * 22;
                
                // Obtener el registro con el peso más cercano menor o igual al buscado
                $pesoBuscado = $produccion->peso_transferencia;
                $tabla = TablaAlimentacion::where('pesos', '<=', $pesoBuscado)->orderBy('pesos', 'desc')->first();

                if($produccion->tabla_alimentacion == "ta1"){
                    $data['peso_corporal'] = $tabla->ta1;
                }elseif($produccion->tabla_alimentacion == "ta2"){
                    $data['peso_corporal'] = $tabla->ta2;
                }elseif($produccion->tabla_alimentacion == "ta3"){
                    $data['peso_corporal'] = $tabla->ta3;
                }elseif($produccion->tabla_alimentacion == "ta4"){
                    $data['peso_corporal'] = $tabla->ta4;
                }elseif($produccion->tabla_alimentacion == "ta5"){
                    $data['peso_corporal'] = $tabla->ta5;
                }elseif($produccion->tabla_alimentacion == "ta6"){
                    $data['peso_corporal'] = $tabla->ta6;
                }elseif($produccion->tabla_alimentacion == "ta7"){
                    $data['peso_corporal'] = $tabla->ta7;
                }
                
                // Definir las variables
                $p4 = $data['biomasa']; // Verifica si $data['biomasa'] tiene el valor esperado
                $q4 = $data['peso_corporal']/100; // Verifica si $data['peso_corporal'] tiene el valor esperado
                $b6 = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
                $b12 = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
                // Aplicar la fórmula utilizando la función de redondeo personalizada
                $resultado = $this->redondeo_mult((($p4 * $q4) / 2.20462) * $b6, $b12);
                
                $data['alimento_dia'] = $resultado;
                $data['alimento_area'] = number_format(($data['alimento_dia'] / $produccion->piscina->area_ha), 2);
                $data['alimento_aculumado'] = $data['alimento_dia'];
                
                $data['roi'] = 0;
            }elseif($data['num_dia'] > 1){
                $registroAnterior = ProyectoCultivo::find($registroProyecto->id);

                if ($data['num_dia'] < 15) {
                    $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[0]['crecimiento'];
                } elseif ($data['num_dia'] >= 15 && $data['num_dia'] < 30) {
                    $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[1]['crecimiento'];
                } elseif ($data['num_dia'] >= 30 && $data['num_dia'] < 45) {
                    $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[2]['crecimiento'];
                } elseif ($data['num_dia'] >= 45 && $data['num_dia'] < 60) {
                    $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[3]['crecimiento'];
                } elseif ($data['num_dia'] >= 60) {
                    $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[4]['crecimiento'];
                }
                
                $data['crecimiento_lineal'] = ( $data['peso_proyecto'] - $produccion->peso_transferencia ) / $data['num_dia'];
                
                $data['densidad_raleada'] = 0;

                if ($data['num_dia'] < 30) {
                    $resultadoSupervivencia = ((($registroAnterior->supervivencia_base/100) - ((100/100 - $produccion->supervivencia_30/100 )/29))*100) - ($data['densidad_raleada']/$produccion->densidad);
                    $data['supervivencia_base'] = $resultadoSupervivencia;
                }elseif ($data['num_dia'] == 30) {
                    $resultadoSupervivencia = ( ($produccion->supervivencia_30) - ($data['densidad_raleada']/$produccion->densidad) ) -  ( $sumaDensRal/$produccion->densidad ) ;
                    $data['supervivencia_base'] = $resultadoSupervivencia;
                }elseif ($data['num_dia'] > 30) {
                    $resultadoSupervivencia = ((($registroAnterior->supervivencia_base/100) - (( ($produccion->supervivencia_30/100)  - ($produccion->supervivencia_fin/100) ) / ($produccion->dias_ciclo - 30) ))*100) - ($data['densidad_raleada']/$produccion->densidad);
                    $data['supervivencia_base'] = $resultadoSupervivencia;
                }
                
                $data['densidad'] = ( ( $data['supervivencia_base'] * $produccion->densidad )/100 );
                $data['biomasa_raleada'] = (($data['densidad_raleada'] * $data['peso_proyecto'] * 22) + $registroAnterior->biomasa_raleada);
                $data['biomasa'] = $data['densidad'] * $data['peso_proyecto'] * 22;
                #dd($data['biomasa']);
            
                // Obtener el registro con el peso más cercano menor o igual al buscado
                $pesoBuscado = $data['peso_proyecto'];
                $tabla = TablaAlimentacion::where('pesos', '<=', $pesoBuscado)->orderBy('pesos', 'desc')->first();

                if($produccion->tabla_alimentacion == "ta1"){
                    $data['peso_corporal'] = $tabla->ta1;
                }elseif($produccion->tabla_alimentacion == "ta2"){
                    $data['peso_corporal'] = $tabla->ta2;
                }elseif($produccion->tabla_alimentacion == "ta3"){
                    $data['peso_corporal'] = $tabla->ta3;
                }elseif($produccion->tabla_alimentacion == "ta4"){
                    $data['peso_corporal'] = $tabla->ta4;
                }elseif($produccion->tabla_alimentacion == "ta5"){
                    $data['peso_corporal'] = $tabla->ta5;
                }elseif($produccion->tabla_alimentacion == "ta6"){
                    $data['peso_corporal'] = $tabla->ta6;
                }elseif($produccion->tabla_alimentacion == "ta7"){
                    $data['peso_corporal'] = $tabla->ta7;
                }
            
                // Definir las variables
                $p4 = $data['biomasa']; // Verifica si $data['biomasa'] tiene el valor esperado
                $q4 = $data['peso_corporal']/100; // Verifica si $data['peso_corporal'] tiene el valor esperado
                $b6 = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
                $b12 = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
                // Aplicar la fórmula utilizando la función de redondeo personalizada
                $resultado = $this->redondeo_mult((($p4 * $q4) / 2.20462) * $b6, $b12);
                
                $data['alimento_dia'] = $resultado;
                $data['alimento_area'] = number_format(($data['alimento_dia'] / $produccion->piscina->area_ha), 2);
                $data['alimento_aculumado'] = $registroAnterior->alimento_aculumado + $data['alimento_dia'];
                #dd($data['alimento_aculumado']);
                
                $data['roi'] = 0;
            }
            
            $u4 = $data['alimento_aculumado'];
            $p4 = $data['biomasa'];
            $o4 = $data['biomasa_raleada'];
            $b10 = $produccion->peso_transferencia;
            $b8 = $produccion->densidad;
            $b6 = $produccion->piscina->area_ha;

            $resultado_p2 = ((((($p4 + $o4) - ($b10 * $b8 * 22)) / 2.20462) * $b6));
            
            if($resultado_p2 != 0){
                $data['fca'] = ($u4 / $resultado_p2);
            }else{
                $data['fca'] = 0;
            }
            
            $data['id_produccion'] = $id;
            $data['fecha'] = $fechaProyecto;
            $data['dia'] = $diaSemana;
            $dia_anterior = $data['num_dia'];
            $data['id_balanceado'] = 2;
            $registroProyecto = ProyectoCultivo::create($data);
            $sumaDensRal += $data['densidad_raleada'];
        }
    }

    function redondeo_mult($numero, $multiplo) {
        return round($numero / $multiplo) * $multiplo;
    }

    public function borrarProyecto($produccionId)
    {
        ProyectoCultivo::where('id_produccion', $produccionId)->delete();
    }
    
    public function updProyItem(Request $request, string $id)
    {
        $item = ProyectoCultivo::find($id);
        $produccion = Produccion::find($item->id_produccion);

        $request->validate([
            'densidad_raleada' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ]);

        $densidad_raleada = $request->input('densidad_raleada');

        if($item->num_dia == 1){
            $data['densidad_raleada'] = $densidad_raleada;
            $data['supervivencia_base'] = 100;
            $data['densidad'] = ( ( $data['supervivencia_base'] * $produccion->densidad )/100 ) - $densidad_raleada; //0 representa densidad raleada

            $data['biomasa_raleada'] = number_format(($densidad_raleada * $item->peso_proyecto * 22), 0);

            $data['biomasa'] = $data['densidad'] * $item->peso_proyecto * 22;
            
            // Obtener el registro con el peso más cercano menor o igual al buscado
            $pesoBuscado = $produccion->peso_transferencia;
            $tabla = TablaAlimentacion::where('pesos', '<=', $pesoBuscado)->orderBy('pesos', 'desc')->first();

            if($produccion->tabla_alimentacion == "ta1"){
                $data['peso_corporal'] = $tabla->ta1;
            }elseif($produccion->tabla_alimentacion == "ta2"){
                $data['peso_corporal'] = $tabla->ta2;
            }elseif($produccion->tabla_alimentacion == "ta3"){
                $data['peso_corporal'] = $tabla->ta3;
            }elseif($produccion->tabla_alimentacion == "ta4"){
                $data['peso_corporal'] = $tabla->ta4;
            }elseif($produccion->tabla_alimentacion == "ta5"){
                $data['peso_corporal'] = $tabla->ta5;
            }elseif($produccion->tabla_alimentacion == "ta6"){
                $data['peso_corporal'] = $tabla->ta6;
            }elseif($produccion->tabla_alimentacion == "ta7"){
                $data['peso_corporal'] = $tabla->ta7;
            }
            
            // Definir las variables
            $p4 = $data['biomasa']; // Verifica si $data['biomasa'] tiene el valor esperado
            $q4 = $data['peso_corporal']/100; // Verifica si $data['peso_corporal'] tiene el valor esperado
            $b6 = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
            $b12 = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
            // Aplicar la fórmula utilizando la función de redondeo personalizada
            $resultado = $this->redondeo_mult((($p4 * $q4) / 2.20462) * $b6, $b12);
            
            $data['alimento_dia'] = $resultado;
            $data['alimento_area'] = number_format(($data['alimento_dia'] / $produccion->piscina->area_ha), 2);
            $data['alimento_aculumado'] = $data['alimento_dia'];
        }else{
            
            $registroAnterior = ProyectoCultivo::where('id_produccion', $produccion->id)
                                                    ->where('num_dia', ($item->num_dia - 1))
                                                    ->first();

            $data['densidad_raleada'] = $densidad_raleada;

            if ($item->num_dia < 30) {
                $resultadoSupervivencia = ((($registroAnterior->supervivencia_base*0.01) - ((1 - ($produccion->supervivencia_30*0.01) )/29))) - ($data['densidad_raleada']/$produccion->densidad);
                $data['supervivencia_base'] = $resultadoSupervivencia*100;
            }elseif ($item->num_dia == 30) {
                $sumaDensRal = ProyectoCultivo::where('id_produccion', $produccion->id)
                                ->whereBetween('num_dia', [1, 29])
                                ->sum('densidad_raleada');
                $sumaDensRal += $densidad_raleada;
                $resultadoSupervivencia = ( ($produccion->supervivencia_30*0.01) - ($data['densidad_raleada']/$produccion->densidad) ) -  ( $sumaDensRal/$produccion->densidad ) ;
                $data['supervivencia_base'] = $resultadoSupervivencia*100;
            }elseif ($item->num_dia > 30) {
                $resultadoSupervivencia = ((($registroAnterior->supervivencia_base/100) - (( ($produccion->supervivencia_30/100)  - ($produccion->supervivencia_fin/100) ) / ($produccion->dias_ciclo - 30) ))*100) - ($data['densidad_raleada']/$produccion->densidad);
                $data['supervivencia_base'] = $resultadoSupervivencia;
            }

            $data['densidad'] = ( ($data['supervivencia_base']*0.01) * $produccion->densidad );

            $data['biomasa_raleada'] = ($data['densidad_raleada'] * $item->peso_proyecto * 22) + $registroAnterior->biomasa_raleada;

            $data['biomasa'] = $data['densidad'] * $item->peso_proyecto * 22;
            
            // Obtener el registro con el peso más cercano menor o igual al buscado
            $pesoBuscado = $item->peso_proyecto;
            $tabla = TablaAlimentacion::where('pesos', '<=', $pesoBuscado)->orderBy('pesos', 'desc')->first();

            if($produccion->tabla_alimentacion == "ta1"){
                $data['peso_corporal'] = $tabla->ta1;
            }elseif($produccion->tabla_alimentacion == "ta2"){
                $data['peso_corporal'] = $tabla->ta2;
            }elseif($produccion->tabla_alimentacion == "ta3"){
                $data['peso_corporal'] = $tabla->ta3;
            }elseif($produccion->tabla_alimentacion == "ta4"){
                $data['peso_corporal'] = $tabla->ta4;
            }elseif($produccion->tabla_alimentacion == "ta5"){
                $data['peso_corporal'] = $tabla->ta5;
            }
            
            // Definir las variables
            $p4 = $data['biomasa']; // Verifica si $data['biomasa'] tiene el valor esperado
            $q4 = $data['peso_corporal']*0.01; // Verifica si $data['peso_corporal'] tiene el valor esperado
            $b6 = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
            $b12 = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
            // Aplicar la fórmula utilizando la función de redondeo personalizada
            $resultado = $this->redondeo_mult((($p4 * $q4) / 2.20462) * $b6, $b12);
            
            $data['alimento_dia'] = $resultado;
            $data['alimento_area'] = number_format(($data['alimento_dia'] / $produccion->piscina->area_ha), 2);
            $data['alimento_aculumado'] = $data['alimento_dia'] + $registroAnterior->alimento_aculumado;
        }


        $item->update($data);

        $items = ProyectoCultivo::where('id_produccion', $produccion->id)->orderBy('num_dia', 'ASC')->get();

        ProyectoCultivo::where('id_produccion', $produccion->id)
                        ->where('num_dia', '>', $item->num_dia)
                        ->delete();
                        
        $this->nuevosItemsProy($item->id);
        
        return back()->with('success', 'Registro Actualizado');
    }

    function nuevosItemsProy($id){

        $item = ProyectoCultivo::find($id);
        $produccion = Produccion::find($item->id_produccion);

        $balanceado = Balanceado::where('estado', 1)->first();
        $dias = $produccion->dias_ciclo;
        $ultimaFecha = Carbon::parse($item->fecha)->addDay();
        $crecimientoProy = [
            ["min" => 0, "max" => 15, "crecimiento" => $produccion->crecimiento1],
            ["min" => 15, "max" => 30, "crecimiento" => $produccion->crecimiento2],
            ["min" => 30, "max" => 45, "crecimiento" => $produccion->crecimiento3],
            ["min" => 45, "max" => 60, "crecimiento" => $produccion->crecimiento4],
            ["min" => 60, "max" => PHP_INT_MAX, "crecimiento" => $produccion->crecimiento5], // PHP_INT_MAX para manejar el caso "> 60"
        ];
        #$sumaDensRal = 0;
        for ($i = $item->num_dia; $i < $dias; $i++) {
            $data = [];
            $data['num_dia'] = $i+1;
            $fechaProyecto = $ultimaFecha->copy()->addDays($i);
            $diaSemana = $fechaProyecto->locale('es')->isoFormat('dddd');

            if($i == $item->num_dia){
                $registroAnterior = ProyectoCultivo::find($id);
            }else{
                $registroAnterior = ProyectoCultivo::find($registroProyecto->id);
            }

            if ($data['num_dia'] < 15) {
                $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[0]['crecimiento'];
            } elseif ($data['num_dia'] >= 15 && $data['num_dia'] < 30) {
                $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[1]['crecimiento'];
            } elseif ($data['num_dia'] >= 30 && $data['num_dia'] < 45) {
                $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[2]['crecimiento'];
            } elseif ($data['num_dia'] >= 45 && $data['num_dia'] < 60) {
                $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[3]['crecimiento'];
            } elseif ($data['num_dia'] >= 60) {
                $data['peso_proyecto'] = $registroAnterior->peso_proyecto + $crecimientoProy[4]['crecimiento'];
            }
            
            $data['crecimiento_lineal'] = ( $data['peso_proyecto'] - $produccion->peso_transferencia ) / $data['num_dia'];
            
            $data['densidad_raleada'] = 0;

            if ($data['num_dia'] < 30) {
                $resultadoSupervivencia = ((($registroAnterior->supervivencia_base*0.01) - (((1) - ($produccion->supervivencia_30*0.01) )/29))) - ($data['densidad_raleada']/$produccion->densidad);
                $data['supervivencia_base'] = $resultadoSupervivencia*100;
            }elseif ($data['num_dia'] == 30) {
                $sumaDensRal = ProyectoCultivo::where('id_produccion', $produccion->id)
                                ->whereBetween('num_dia', [1, 29])
                                ->sum('densidad_raleada');

                $resultadoSupervivencia = ( ($produccion->supervivencia_30*0.01) - ($data['densidad_raleada']/$produccion->densidad) ) -  ( $sumaDensRal/$produccion->densidad ) ;
                $data['supervivencia_base'] = $resultadoSupervivencia*100;
            }elseif ($data['num_dia'] > 30) {
                $resultadoSupervivencia = ((($registroAnterior->supervivencia_base/100) - (( ($produccion->supervivencia_30/100)  - ($produccion->supervivencia_fin/100) ) / ($produccion->dias_ciclo - 30) ))*100) - ($data['densidad_raleada']/$produccion->densidad);
                $data['supervivencia_base'] = $resultadoSupervivencia;
            }
            
            $data['densidad'] = ( ($data['supervivencia_base']*0.01) * $produccion->densidad );
            $data['biomasa_raleada'] = (($data['densidad_raleada'] * $data['peso_proyecto'] * 22) + $registroAnterior->biomasa_raleada);
            $data['biomasa'] = $data['densidad'] * $data['peso_proyecto'] * 22;
            #dd($data['biomasa']);
        
            // Obtener el registro con el peso más cercano menor o igual al buscado
            $pesoBuscado = $data['peso_proyecto'];
            $tabla = TablaAlimentacion::where('pesos', '<=', $pesoBuscado)->orderBy('pesos', 'desc')->first();

            if($produccion->tabla_alimentacion == "ta1"){
                $data['peso_corporal'] = $tabla->ta1;
            }elseif($produccion->tabla_alimentacion == "ta2"){
                $data['peso_corporal'] = $tabla->ta2;
            }elseif($produccion->tabla_alimentacion == "ta3"){
                $data['peso_corporal'] = $tabla->ta3;
            }elseif($produccion->tabla_alimentacion == "ta4"){
                $data['peso_corporal'] = $tabla->ta4;
            }elseif($produccion->tabla_alimentacion == "ta5"){
                $data['peso_corporal'] = $tabla->ta5;
            }
        
            // Definir las variables
            $p4 = $data['biomasa']; // Verifica si $data['biomasa'] tiene el valor esperado
            $q4 = $data['peso_corporal']/100; // Verifica si $data['peso_corporal'] tiene el valor esperado
            $b6 = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
            $b12 = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
            // Aplicar la fórmula utilizando la función de redondeo personalizada
            $resultado = $this->redondeo_mult((($p4 * $q4) / 2.20462) * $b6, $b12);
            
            $data['alimento_dia'] = $resultado;
            $data['alimento_area'] = number_format(($data['alimento_dia'] / $produccion->piscina->area_ha), 2);
            $data['alimento_aculumado'] = $registroAnterior->alimento_aculumado + $data['alimento_dia'];
            #dd($data['alimento_aculumado']);
            
            $data['roi'] = 0;
            
            $u4 = $data['alimento_aculumado'];
            $p4 = $data['biomasa'];
            $o4 = $data['biomasa_raleada'];
            $b10 = $produccion->peso_transferencia;
            $b8 = $produccion->densidad;
            $b6 = $produccion->piscina->area_ha;

            $resultado_p2 = ((((($p4 + $o4) - ($b10 * $b8 * 22)) / 2.20462) * $b6));
            
            if($resultado_p2 != 0){
                $data['fca'] = ($u4 / $resultado_p2);
            }else{
                $data['fca'] = 0;
            }
            
            $data['id_produccion'] = $produccion->id;
            $data['fecha'] = $fechaProyecto;
            $data['dia'] = $diaSemana;
            $dia_anterior = $data['num_dia'];
            $data['id_balanceado'] = 2;

            $registroProyecto = ProyectoCultivo::create($data);
            #$sumaDensRal += $data['densidad_raleada'];
        }
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
        $vista = "Consultar Producción " . $produccion->fecha . " - " . $produccion->dias_ciclo . " días - " . $produccion->piscina->camaronera->nombre . ': ' . $produccion->piscina->nombre;
        $proyectoItems = ProyectoCultivo::where('id_produccion', $id)->orderBy('num_dia', 'asc')->get();
        $produccionItems = ProyectoReal::where('id_produccion', $id)->orderBy('num_dia', 'asc')->get();
        return view('produccion.show', compact('grupo', 'modulo', 'vista', 'produccion', 'proyectoItems', 'produccionItems'));
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

            'crecimiento1' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento2' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento3' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento4' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'crecimiento5' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
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

        // Llamar a la función borrarProyecto después de actualizar la producción y luego crear nuevo proyecto
        $this->borrarProyecto($produccion->id);
        $this->crearProyecto($produccion->id);
        
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
    
    public function crearItemReal(string $id)
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
}
