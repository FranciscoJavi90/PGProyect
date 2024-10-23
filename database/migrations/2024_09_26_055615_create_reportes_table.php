<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportesTable extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alumno')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('id_curso')->constrained('cursos')->onDelete('cascade');
            $table->text('informe');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
}
