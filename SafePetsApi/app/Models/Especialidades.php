<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidades extends Model
{
    protected $fillable = [
        'nombre_e',
    ];
    public function medicos(){
        return $this->hasMany(Medicos::class, 'id'); // Relación de uno a muchos
    }
}
