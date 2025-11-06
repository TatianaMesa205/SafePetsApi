<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacunasMascotas extends Model
{
    protected $table = 'vacunas_mascotas';
    protected $primaryKey = 'id_vacunas_mascotas';

    protected $fillable = [
        'id_mascotas',
        'id_vacunas',
        'fecha_aplicacion',
        'proxima_dosis',
    ];

    // Relaciones
    public function mascota()
    {
        return $this->belongsTo(Mascotas::class, 'id_mascotas', 'id_mascotas');
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacunas::class, 'id_vacunas', 'id_vacunas');
    }
}
