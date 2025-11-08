<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuarios';

    protected $fillable = [
        'nombre_usuario',
        'email',
        'contrasena',
        'id_roles',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // ✅ Si algún día usas casting automático de hash (no obligatorio)
    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed', // encriptar
        ];
    }

    // ✅ Asignacion de password a contraseña 
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function role()
    {
        return $this->belongsTo(Roles::class, 'id_roles', 'id_roles');
    }
}
