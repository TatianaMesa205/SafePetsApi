<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuarios';
    public $timestamps = false; 

    protected $fillable = [
        'nombre_usuario',
        'email',
        'password',
        'id_roles',
    ];

    protected $hidden = [
        'password',
    ];

    // ✅ Relación con la tabla de roles
    public function role()
    {
        return $this->belongsTo(Roles::class, 'id_roles', 'id_roles');
    }

    // ✅ Indica a Laravel qué campo usar para la contraseña (opcional, pero seguro)
    public function getAuthPassword()
    {
        return $this->password;
    }
}
