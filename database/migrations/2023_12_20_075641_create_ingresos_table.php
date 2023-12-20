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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ta', 191)->nullable();
            $table->string('temp', 191)->nullable();
            $table->string('frec', 191)->nullable();
            $table->string('peso', 191)->nullable();
            $table->string('talla', 191)->nullable();
            $table->string('fcf', 191)->nullable();
            $table->string('frec_card', 191)->nullable();
            $table->string('saturacion', 191)->nullable();
            $table->string('silverman', 191)->nullable();
            $table->string('glasgow', 191)->nullable();
            $table->text('cuadro_clinico')->nullable();
            $table->text('exploracion_medica')->nullable();
            $table->string('diagnosticomotivo', 191)->nullable();
            $table->string('diagnostico2', 191)->nullable();
            $table->string('opcion_diagnostico2', 191)->nullable();
            $table->string('diagnostico3', 191)->nullable();
            $table->string('opcion_diagnostico3', 191)->nullable();
            $table->string('tratamiento', 191)->nullable();
            $table->string('pronostico', 191)->nullable();
            $table->bigInteger('appointment_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('doctor_id')->nullable();
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
        Schema::dropIfExists('ingresos');
    }
};
