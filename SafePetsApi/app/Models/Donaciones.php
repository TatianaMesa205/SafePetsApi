<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donaciones extends Model
{
    protected $table = 'donaciones';
    protected $primaryKey = 'id_donaciones';
    public $timestamps = false; // La tabla no tiene created_at ni updated_at

    protected $fillable = [
        'id_usuarios',
        'codigo_referencia',
        'monto',
        'estado_pago',
        'transaccion_id_externa',
        'fecha',
        'metodo_pago',
    ];

    // Relación con usuarios
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'id_usuarios', 'id_usuarios');
    }
}
