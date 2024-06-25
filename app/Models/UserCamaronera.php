<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCamaronera extends Model
{
    use HasFactory;
    
    protected $table = 'usuarios_camaroneras';
    protected $fillable = ['id_user','id_camaronera'];
    
    public $timestamps = true;

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function camaronera()
    {
        return $this->belongsTo(Camaronera::class, 'id_camaronera');
    }
}
