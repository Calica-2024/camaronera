<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosBalanceado;
use App\Models\Camaronera;
use App\Models\UserCamaronera;
use App\Models\Balanceado;

class MovimientoBalanceadoController extends Controller
{
    protected $grupo = "Inventario";
    protected $modulo = "InvBalanceado";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $vista = "Inventario";
        $camaronerasUser = UserCamaronera::where('id_user', auth()->id())->get();
        return view('inventario.balanceados.index', compact('grupo', 'modulo', 'vista', 'camaronerasUser'));
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
    public function store(Request $request, $camaroneraId, $balanceadoId)
    {

        // Validar los datos del request
        $data = $request->validate([
            'tipo_movimiento' => [
                'required',
                'in:entrada,salida', // Validar que sea 'entrada' o 'salida'
            ],
            'cantidad' => [
                'required',
                'numeric', // Debe ser un número
                'min:0.01', // Debe ser positivo o cero
                'regex:/^\d*(\.\d{1,2})?$/', // Permitir solo números decimales con hasta dos decimales
            ],
            'descripcion' => [
                'required',
                'string',
                'max:250', // No exceder los 250 caracteres
            ],
        ]);
        $data['id_camaronera'] = $camaroneraId;
        $data['id_balanceado'] = $balanceadoId;

        MovimientosBalanceado::create($data);
        return back()->with('success', 'El ingreso de inventario se ha guardado exitosamente.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $camaronera = Camaronera::find($id);
        $vista = "Inventario " . $camaronera->nombre;
        $balanceados = Balanceado::get();
        $movimientos = MovimientosBalanceado::where('id_camaronera', $id)->whereIn('id_balanceado', $balanceados->pluck('id'))->get();
        return view('inventario.balanceados.show', compact('grupo', 'modulo', 'camaronera', 'vista', 'balanceados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function movimientos($camaroneraId, $balanceadoId)
    {
        $grupo = $this->grupo;
        $modulo = $this->modulo;
        $camaronera = Camaronera::find($camaroneraId);
        $balanceado = Balanceado::find($balanceadoId);
        $vista = "Movimientos de Inventario " . $camaronera->nombre;
        $movimientos = MovimientosBalanceado::where('id_camaronera', $camaroneraId)
                                            ->where('id_balanceado', $balanceadoId)
                                            ->orderBy('fecha_movimiento', 'desc')
                                            ->get();

        // Calcular stock en una sola consulta
        $stock = MovimientosBalanceado::where('id_camaronera', $camaroneraId)
                                        ->where('id_balanceado', $balanceadoId)
                                        ->selectRaw('SUM(CASE WHEN tipo_movimiento = "entrada" THEN cantidad ELSE 0 END) -
                                                    SUM(CASE WHEN tipo_movimiento = "salida" THEN cantidad ELSE 0 END) AS stock')
                                        ->pluck('stock')
                                        ->first();
        return view('inventario.balanceados.movimientos', compact('grupo', 'modulo', 'camaronera', 'vista', 'balanceado', 'movimientos', 'stock'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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
