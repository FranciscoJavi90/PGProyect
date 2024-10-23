<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnosTable extends Migration
{
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento');
            $table->string('CUI', 13)->unique();
            $table->string('genero', 20);
            $table->foreignId('id_curso')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
}
