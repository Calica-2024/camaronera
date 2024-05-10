<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piscina extends Model
{
    use HasFactory;
    
    protected $table = 'piscinas';
    protected $fillable = ['nombre','numero','id_camaronera','estado'];
    
    public $timestamps = true;
}
