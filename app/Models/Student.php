<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'clave', 'nombre', 'apellidos', 'fecha_nacimiento', 'CUI', 'genero', 'id_curso', 'id_usuario', 'created_at', 'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'id_curso');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'id_alumno');
    }
}