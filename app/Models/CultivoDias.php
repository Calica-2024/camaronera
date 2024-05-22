<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultivoDias extends Model
{
    use HasFactory;

    protected $table = 'cultivos';

    protected $fillable = [
        'id_produccion',
        'fecha',
        'dia',
        'pero_proyecto',
        'crecimiento_lineal',
        'supervivencia_base',
        'densidad',
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
}
