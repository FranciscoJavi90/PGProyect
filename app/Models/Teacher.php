<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'nombre_completo', 
        'titulo_profesional', 
        'especialidad', 
        'numero_empleado', 
        'id_usuario'
    ];
}