<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscripcionesTable extends Migration
{
    public function up()
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_curso')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('id_alumno')->constrained('alumnos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscripciones');
    }
}
