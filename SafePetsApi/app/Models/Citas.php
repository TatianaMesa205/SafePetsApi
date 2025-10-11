<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
     protected $fillable = [
        'id_pacientes',
        'id_medicos',
        'id_consultorios',
        'fecha',
        'hora',
        'estado', // Por ejemplo: 'pendiente', 'confirmada', 'cancelada'
        'motivo',
    ];
    public function paciente(){
        return $this->belongsTo(Pacientes::class, 'id_pacientes', 'id'); // belongsTo significa de muchos a uno
    }
    public function medico(){
        return $this->belongsTo(Medicos::class, 'id_medicos', 'id'); // belongsTo significa de muchos a uno
    }
    public function consultorio(){
        return $this->belongsTo(Consultorios::class, 'id_consultorios', 'id'); // belongsTo significa de muchos a uno
    }
}
