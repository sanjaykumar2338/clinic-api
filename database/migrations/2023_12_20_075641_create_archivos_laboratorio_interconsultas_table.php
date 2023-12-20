<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_laboratorio_interconsultas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id')->index('archivos_laboratorio_interconsultas_appointment_id_foreign');
            $table->unsignedBigInteger('interconsulta_id')->index('archivos_laboratorio_interconsultas_interconsulta_id_foreign');
            $table->text('archivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivos_laboratorio_interconsultas');
    }
};
