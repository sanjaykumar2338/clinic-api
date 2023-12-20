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
        Schema::create('archivos_evidencia_laboratorios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id')->index('archivos_evidencia_laboratorios_appointment_id_foreign');
            $table->unsignedBigInteger('evoluciones_id')->index('archivos_evidencia_laboratorios_evoluciones_id_foreign');
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
        Schema::dropIfExists('archivos_evidencia_laboratorios');
    }
};
