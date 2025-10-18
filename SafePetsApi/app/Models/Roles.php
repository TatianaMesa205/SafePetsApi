<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_roles';

    protected $fillable = [
        'nombre_rol',
    ];

    // Relación de un rol con muchos usuarios
    public function usuario()
    {
        return $this->hasMany(Usuarios::class, 'id_roles', 'id_roles');
    }
}
