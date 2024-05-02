<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camaronera extends Model
{
    use HasFactory;
    
    protected $table = 'camaroneras';
    protected $fillable = ['nombre','direccion','estado'];
    
    public $timestamps = true;
}
