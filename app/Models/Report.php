<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_alumno'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_alumno');
    }

    public function analysisIA()
    {
        return $this->hasMany(AnalysisIA::class, 'id_reporte');
    }
}