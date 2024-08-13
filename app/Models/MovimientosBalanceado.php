<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosBalanceado extends Model
{
    use HasFactory;
    
    protected $table = 'movimientos_balanceado';

    protected $fillable = [
        'id_camaronera',
        'id_balanceado',
        'tipo_movimiento',
        'cantidad',
        'id_p_real',
        'fecha_movimiento',
        'descripcion',
    ];

    public $timestamps = true;
    
    public function balanceado()
    {
        return $this->belongsTo(Balanceado::class, 'id_balanceado');
    }
    
    public function dia_real()
    {
        return $this->belongsTo(ProyectoReal::class, 'id_p_real');
    }
}
