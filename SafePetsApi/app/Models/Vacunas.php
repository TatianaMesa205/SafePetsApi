<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacunas extends Model
{
    protected $table = 'vacunas';
    protected $primaryKey = 'id_vacunas';

    protected $fillable = [
        'nombre_vacuna',
        'tiempo_aplicacion',
    ];

    // Relaciones
    public function vacunasMascotas()
    {
        return $this->hasMany(VacunasMascotas::class, 'id_vacunas', 'id_vacunas');
    }
}
