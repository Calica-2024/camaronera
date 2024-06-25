<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoReal extends Model
{
    use HasFactory;
    
    protected $table = 'proyecto_real';

    protected $fillable = [
        'id_produccion',
        'fecha',
        'dia',
        'num_dia',
        'peso_real',
        'peso_real_anterior',
        'alimento',
        'alimento_calculo',
        'peso_corporal',
        'densidad_consumo',
        'densidad_muestreo',
        'densidad_actual',
        'supervivencia',
        'densidad_raleada',
        'densidad_raleada_acumulada',
        'biomasa_raleada',
        'biomasa_actual',
        'recomendacion_alimento',
        'alimento_acumulado',
        'fca',
        'costo_biomasa',
        'up',
        'roi',
        'id_balanceado',
    ];

    public $timestamps = true;
    
    public function balanceado()
    {
        return $this->belongsTo(Balanceado::class, 'id_balanceado');
    }
    
    public function produccion()
    {
        return $this->belongsTo(Produccion::class, 'id_produccion');
    }
}
