<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaAlimentacion extends Model
{
    use HasFactory;

    protected $table = 'tabla_alimentacion';

    protected $fillable = [
        'pesos',
        'ta1',
        'ta2',
        'ta3',
        'ta4',
        'ta5',
    ];

    public $timestamps = true;
}
