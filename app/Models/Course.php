<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'cursos'; // Especificar el nombre de la tabla

    protected $fillable = [
        'nombre', 'descripcion', 'id_profesor' // Asegurarse de que 'id_profesor' esté en la lista de fillable
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'id_curso');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_profesor');
    }
}