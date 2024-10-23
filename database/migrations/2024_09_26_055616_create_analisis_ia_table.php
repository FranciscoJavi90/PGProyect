<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisIATable extends Migration
{
    public function up()
    {
        Schema::create('analisis_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reporte')->constrained('reportes')->onDelete('cascade');
            $table->date('fecha_analisis');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('analisis_ia');
    }
}
