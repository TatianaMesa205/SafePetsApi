<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donaciones extends Model
{
    protected $table = 'donaciones';
    protected $primaryKey = 'id_donaciones';

    protected $fillable = [
        'id_usuarios',
        'monto',
        'fecha',
        'metodo_pago',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'id_usuarios', 'id_usuarios');
    }
}
