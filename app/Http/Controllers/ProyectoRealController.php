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
use App\Models\MovimientosBalanceado;

class ProyectoRealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $id)
    {
        $produccion = Produccion::find($id);
        if(!$produccion){
            return redirect('dashboard')->withErrors('El Registro No Existe');
        }

        $dia = $request->num_dia;
        $diaExiste = ProyectoReal::where('id_produccion', $id)->where('num_dia', $dia)->first();
        if($diaExiste){
            return back()->withErrors('Ya Existe Un Registro En Este Día');
        }

        if($dia != 1){
            $diaAnterior = $dia - 1;
            $proyAnterior = ProyectoReal::where('id_produccion', $id)->where('num_dia', $diaAnterior)->first();
        }

        $request->validate([
            'num_dia' => [
                'required',
                'numeric',
            ],
            'fecha' => [
                'required',
            ],
            'peso_real' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'alimento' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'alimento_calculo' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'densidad_muestreo' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'densidad_actual' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'densidad_oficina' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'densidad_raleada' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'id_balanceado' => [
                'required',
                'numeric',
            ],
        ]);
        $data['num_dia'] = $request->num_dia;
        $data['fecha'] = $request->fecha;
        if($request->peso_real == 0 || empty($request->peso_real)){
            $data['peso_real'] = null;
        }else{
            $data['peso_real'] = $request->peso_real;
        }
        $data['alimento'] = $request->alimento;
        $data['alimento_calculo'] = $request->alimento_calculo;
        $data['densidad_muestreo'] = $request->densidad_muestreo;
        if($data['num_dia'] != 1){
            $data['densidad_actual'] = $request->densidad_actual == 0 ? $proyAnterior->densidad_actual : $request->densidad_actual;
        }else{
            $data['densidad_actual'] = $request->densidad_actual == 0 ? $produccion->densidad : $request->densidad_actual;
        }
        $data['densidad_raleada'] = $request->densidad_raleada;
        $data['id_produccion'] = $id;
        // Convertir la fecha a un objeto Carbon
        $fecha = Carbon::parse($request->fecha);
        // Establecer el locale a español y obtener el día de la semana en formato ISO
        $diaSemana = $fecha->locale('es')->isoFormat('dddd');
        // Asignar el día al array de datos
        $data['dia'] = $diaSemana;
        $data['id_balanceado'] = $request->id_balanceado;

        if($data['num_dia'] == 1){
            $data['peso_real_anterior'] = 0;
            $data['biomasa_raleada'] = $data['densidad_actual'] * $data['peso_real'] * 22;
            $data['biomasa_raleada_acumulada'] = $data['densidad_raleada'];
            $data['alimento_acumulado'] = $data['alimento'];
        }else{
            if($data['peso_real'] > 0){
                $data['peso_real_anterior'] = $data['peso_real'] - $proyAnterior->peso_real;
            }else{
                $data['peso_real_anterior'] = 0;
            }
            $data['alimento_acumulado'] = $data['alimento'] + $proyAnterior->alimento_acumulado;
            $data['biomasa_raleada'] = ($data['densidad_raleada'] * $data['peso_real'] * 22) + $proyAnterior->biomasa_raleada;
            $data['biomasa_raleada_acumulada'] = $data['densidad_raleada'] + $proyAnterior->biomasa_raleada_acumulada;
        }

        if($data['peso_real'] > 0){
            // Obtener el registro con el peso más cercano menor o igual al buscado
            $pesoBuscado = $data['peso_real'];
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
            $divisor1 = ($produccion->piscina->area_ha * 10000);
            $divisor2 = ( $data['peso_real'] * $data['peso_corporal'] );
            if($divisor1 == 0 || $divisor2 == 0){
                $densidad_consumo = null;
            }else{
                $densidad_consumo = (( ($data['alimento_calculo'] / ($produccion->piscina->area_ha * 10000)) * 1000 ) / ( $data['peso_real'] * $data['peso_corporal'] ))*100;
            }
        }else{
            $data['peso_corporal'] = null;
            $densidad_consumo = null;
        }
        
        if($request->peso_real == 0 || empty($request->peso_real)){
            $data['fca'] = null;
            $data['recomendacion_alimento'] = null;
            $data['densidad_consumo'] = null;
            $data['supervivencia'] = null;
            $data['biomasa_actual'] = null;
        }else{

            $data['densidad_consumo'] = $densidad_consumo;
    
            $supervivencia = ($data['densidad_actual'] / $produccion->densidad)*100;
            $data['supervivencia'] = $supervivencia;
            
            $data['biomasa_actual'] = $data['densidad_actual'] * $data['peso_real'] *22;

            
            // Definir las variables
            $biomasaActual = $data['biomasa_actual']; // Verifica si $data['biomasa'] tiene el valor esperado
            $pesoCorporal = $data['peso_corporal']/100; // Verifica si $data['peso_corporal'] tiene el valor esperado
            $area = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
            $multiplo = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
            // Aplicar la fórmula utilizando la función de redondeo personalizada
            $resultado = $this->redondeo_mult((($biomasaActual * $pesoCorporal) / 2.20462) * $area, $multiplo);
            $data['recomendacion_alimento'] = $resultado;
            
            //formula 2
            $alimAcum = $data['alimento_acumulado'];
            $bmActual = $data['biomasa_actual'];
            $bmRal = $data['biomasa_raleada'];
            $pesoProd = $produccion->peso_transferencia;
            $densidad = $produccion->densidad;
            $area = $produccion->piscina->area_ha;
    
            if($data['num_dia'] == 1){
                $data['fca'] = 0;
            }else{
                $formulaFca = ($alimAcum / (((($bmActual + $bmRal) - ($pesoProd * $densidad * 22)) / 2.20462) * $area));
                if($formulaFca != 0){
                    $data['fca'] = $formulaFca;
                }else{
                    $data['fca'] = 0;
                }
            }
        }

        $data['costo_biomasa'] = 0;
        $data['up'] = 0;
        $data['roi'] = 0;

        ProyectoReal::create($data);

        return redirect('producciones/'.$id)->with('success', 'Registro añadido correctamente');
    }

    function redondeo_mult($numero, $multiplo) {
        return round($numero / $multiplo) * $multiplo;
    }

    /**
     * Display the specified resource.
     */
    public function show(ProyectoReal $proyectoReal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProyectoReal $proyectoReal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProyectoReal $id)
    {

        $real = $id;

        if (!$real) {
            return redirect('dashboard')->withErrors('El Registro No Existe');
        }

        $data = $request->validate([
            'peso_real' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'alimento' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'alimento_calculo' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'densidad_muestreo' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'densidad_actual' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'mortalidad' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'densidad_raleada' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'id_balanceado' => [
                'required',
                'numeric',
            ],
        ]);

        $real->update($data);

        $this->procesarUpdate($real);
        
        $inventario = [
            'id_camaronera' => $real->produccion->piscina->camaronera->id,
            'id_balanceado' => $request->id_balanceado,
            'tipo_movimiento' => 'salida',
            'cantidad' => $request->alimento ?? 0,
            'descripcion' => 'Balanceado para producción',
        ];
        
        // Usar updateOrCreate para insertar o actualizar el registro
        MovimientosBalanceado::updateOrCreate(
            ['id_p_real' => $real->id],  // Condición para buscar el registro
            $inventario            // Datos a actualizar o insertar
        );

        return redirect('producciones/'.$real->id_produccion)->with('success', 'Registro actualizado correctamente');
    }

    public function procesarUpdate($real){
        $registroInicial = $real;
        $diaInicial = $registroInicial->num_dia;
        $produccion = Produccion::where('id', $registroInicial->id_produccion)->first();
        $diasProcesar = ProyectoReal::where('id_produccion', $produccion->id)->where('num_dia', '>=', $diaInicial)->orderBy('num_dia', 'ASC')->get();
        
        foreach($diasProcesar as $item){
            if($item->dia != 1){
                $diaAnterior = $item->num_dia - 1;
                $proyAnterior = ProyectoReal::where('id_produccion', $produccion->id)->where('num_dia', $diaAnterior)->first();
            }
            $data['num_dia'] = $item->num_dia;
            $data['fecha'] = $item->fecha;
            $data['peso_real'] = $item->peso_real;
            $data['alimento'] = $item->alimento;
            $data['alimento_calculo'] = $item->alimento_calculo;
            $data['densidad_muestreo'] = $item->densidad_muestreo;
            //$data['densidad_actual'] = $item->densidad_actual == 0 ? $proyAnterior->densidad_actual : $item->densidad_actual;
            $data['densidad_actual'] = $item->densidad_actual;
            $data['densidad_raleada'] = $item->densidad_raleada;
            if($data['num_dia'] == 1){
                $data['peso_real_anterior'] = 0;
                $data['biomasa_raleada'] = $data['densidad_actual'] * $data['peso_real'] * 22;
                $data['biomasa_raleada_acumulada'] = $data['densidad_raleada'];
                $data['alimento_acumulado'] = $data['alimento'];
            }else{
                if($data['peso_real'] > 0){
                    $data['peso_real_anterior'] = $data['peso_real'] - $proyAnterior->peso_real;
                    if ($data['peso_real'] == $data['peso_real_anterior']) {
                        $diaAnterior = $item->num_dia - 1;
                        $pesoAnterior = ProyectoReal::where('id_produccion', $produccion->id)
                                                    ->where('num_dia', $diaAnterior)
                                                    ->first();
                    
                        while ($pesoAnterior && ($pesoAnterior->peso_real === null || $pesoAnterior->peso_real === 0) && $diaAnterior > 0) {
                            $diaAnterior--;
                            $pesoAnterior = ProyectoReal::where('id_produccion', $produccion->id)
                                                        ->where('num_dia', $diaAnterior)
                                                        ->first();
                        }
                    
                        if ($pesoAnterior && $pesoAnterior->peso_real !== null && $pesoAnterior->peso_real !== 0) {
                            $data['peso_real_anterior'] = $data['peso_real'] - $pesoAnterior->peso_real;
                        } else {
                            // Manejar el caso donde no se encontró un registro válido
                            $data['peso_real_anterior'] = 0; // O cualquier otro valor que tenga sentido para tu lógica
                        }
                    }
                }else{
                    $data['peso_real_anterior'] = 0;
                }
                $data['alimento_acumulado'] = $data['alimento'] + $proyAnterior->alimento_acumulado;
                $data['biomasa_raleada'] = ($data['densidad_raleada'] * $data['peso_real'] * 22) + $proyAnterior->biomasa_raleada;
                $data['biomasa_raleada_acumulada'] = $data['densidad_raleada'] + $proyAnterior->biomasa_raleada_acumulada;
            }
            // Obtener el registro con el peso más cercano menor o igual al buscado

            if($data['peso_real'] > 0){
                $pesoBuscado = $data['peso_real'];
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
                $divisor1 = ($produccion->piscina->area_ha * 10000);
                $divisor2 = ( $data['peso_real'] * $data['peso_corporal'] );
                if($divisor1 == 0 || $divisor2 == 0){
                    $densidad_consumo = 0;
                }else{
                    $densidad_consumo = (( ($data['alimento_calculo'] / $divisor1) * 1000 ) / $divisor2)*100;
                }
            }else{
                $data['peso_corporal'] = 0;
                $densidad_consumo = 0;
            }
            $data['densidad_consumo'] = $densidad_consumo;
            $supervivencia = ($data['densidad_actual'] / $produccion->densidad)*100;
            $data['supervivencia'] = $supervivencia;
            $data['biomasa_actual'] = $data['densidad_actual'] * $data['peso_real'] *22;
            // Definir las variables
            $biomasaActual = $data['biomasa_actual']; // Verifica si $data['biomasa'] tiene el valor esperado
            $pesoCorporal = $data['peso_corporal']/100; // Verifica si $data['peso_corporal'] tiene el valor esperado
            $area = $produccion->piscina->area_ha; // Verifica si $produccion->piscina->area_ha tiene el valor esperado
            $multiplo = $produccion->multiplo_redondeo; // Verifica si $produccion->multiplo_redondeo tiene el valor esperado
            // Aplicar la fórmula utilizando la función de redondeo personalizada
            $resultado = $this->redondeo_mult((($biomasaActual * $pesoCorporal) / 2.20462) * $area, $multiplo);
            $data['recomendacion_alimento'] = $resultado;
            //formula 2
            $alimAcum = $data['alimento_acumulado'];
            $bmActual = $data['biomasa_actual'];
            $bmRal = $data['biomasa_raleada'];
            $pesoProd = $produccion->peso_transferencia;
            $densidad = $produccion->densidad;
            $area = $produccion->piscina->area_ha;
            
            if($data['num_dia'] == 1){
                $data['fca'] = 0;
            }else{
                if(((($bmActual + $bmRal) - ($pesoProd * $densidad * 22))) > 0){
                    $formulaFca = ($alimAcum / (((($bmActual + $bmRal) - ($pesoProd * $densidad * 22)) / 2.20462) * $area));
                    if($formulaFca != 0){
                        // Redondear a 2 decimales
                        $data['fca'] = number_format($formulaFca, 2, '.', '');
            
                        // Validar el rango de fca
                        if ($data['fca'] < 0 || $data['fca'] > 10000) {
                            $data['fca'] = 0; // Ajusta este valor según tus necesidades
                        }
                    }else{
                        $data['fca'] = 0;
                    }
                }else{
                    $data['fca'] = 0;
                }
            }
            $data['costo_biomasa'] = 0;
            $data['up'] = 0;
            $data['roi'] = 0;
            $item->update($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProyectoReal $proyectoReal)
    {
        //
    }
}
