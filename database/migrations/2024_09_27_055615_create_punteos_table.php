<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunteosTable extends Migration
{
    public function up()
    {
        Schema::create('punteos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alumno')->constrained('alumnos')->onDelete('cascade'); // Relación con la tabla alumnos
            $table->foreignId('id_inscripcion')->constrained('inscripciones')->onDelete('cascade');
            $table->decimal('nota', 5, 2);  // Para las notas con precisión decimal
            $table->foreignId('id_analisis')->constrained('analisis_ia')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('punteos');
    }
}
