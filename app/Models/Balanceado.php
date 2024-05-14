<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balanceado extends Model
{
    use HasFactory;
    
    protected $table = 'balanceados';
    protected $fillable = ['nombre','tipo','marca','etapa','nomenclatura','unidad_medida','presentacion','precio','estado'];
    
    public $timestamps = true;
}
