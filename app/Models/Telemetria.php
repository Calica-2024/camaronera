<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemetria extends Model
{
    use HasFactory;

    protected $table = 'telemetria';

    protected $fillable = [
        'id_p_real',
        'hora1',
        'temperatura1',
        'oxigeno1',
        'hora2',
        'temperatura2',
        'oxigeno2',
        'hora3',
        'temperatura3',
        'oxigeno3',
    ];

    public $timestamps = true;
}
