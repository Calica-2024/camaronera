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
        'peso_pesca',
        'costo_larva',
        'multiplo_redondeo',
        'supervivencia_30',
        'supervivencia_fin',
        'capacidad_carga',
        'costo_fijo',
        'tabla_alimentacion',
        'estado',
        'crecimiento1',
        'crecimiento2',
        'crecimiento3',
        'crecimiento4',
        'crecimiento5',
    ];
    
    public $timestamps = true;
    
    public function piscina()
    {
        return $this->belongsTo(Piscina::class, 'id_piscina');
    }
}
