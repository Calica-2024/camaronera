<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoCultivo extends Model
{
    use HasFactory;

    protected $table = 'proyecto_cultivo';

    protected $fillable = [
        'id_produccion',
        'fecha',
        'num_dia',
        'dia',
        'peso_proyecto',
        'crecimiento_lineal',
        'supervivencia_base',
        'densidad',
        'densidad_raleada',
        'biomasa_raleada',
        'biomasa',
        'peso_corporal',
        'alimento_dia',
        'alimento_area',
        'id_balanceado',
        'alimento_aculumado',
        'fca',
        'roi',
    ];

    public $timestamps = true;
    
    public function balanceado()
    {
        return $this->belongsTo(Balanceado::class, 'id_balanceado');
    }
}
