<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisIA extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reporte', 'fecha_analisis'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'id_reporte');
    }
}