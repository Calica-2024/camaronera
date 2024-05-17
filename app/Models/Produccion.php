<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;
    
    protected $table = 'producciones';
    
    protected $fillable = [
        'id_piscina',
        'fecha',
        'densidad',
        'dias_ciclo',
        'peso_transferencia',
        'costo_larva',
        'multiplo_redondeo',
        'supervivencia_30',
        'supervivencia_fin',
        'capacidad_carga',
        'costo_fijo',
        'estado',
    ];
    
    public $timestamps = true;
}
