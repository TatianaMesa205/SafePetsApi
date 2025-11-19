<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicaciones extends Model
{
    public $timestamps = false;
    protected $table = 'publicaciones';
    protected $primaryKey = 'id_publicaciones';

    protected $fillable = [
        'tipo',
        'descripcion',
        'foto',
        'fecha_publicacion',
        'contacto',
    ];
}
