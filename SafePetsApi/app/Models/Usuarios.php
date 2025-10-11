<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuarios';

    protected $fillable = [
        'nombre_usuario',
        'email',
        'contraseña',
        'id_roles',
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'contraseña' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'id_roles', 'id_roles');
    }
}
