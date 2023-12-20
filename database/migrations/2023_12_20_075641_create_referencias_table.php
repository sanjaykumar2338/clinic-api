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
        Schema::create('referencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ta', 191)->nullable();
            $table->string('temp', 191)->nullable();
            $table->string('frec', 191)->nullable();
            $table->string('peso', 191)->nullable();
            $table->string('talla', 191)->nullable();
            $table->string('fcf', 191)->nullable();
            $table->string('frec_card', 191)->nullable();
            $table->string('saturacion', 191)->nullable();
            $table->string('motivo', 191)->nullable();
            $table->string('envia', 191)->nullable();
            $table->string('receptor', 191)->nullable();
            $table->string('resumen', 191)->nullable();
            $table->string('diagnostico', 191)->nullable();
            $table->string('diagnostico2', 191)->nullable();
            $table->integer('opcion_diagnostico2')->nullable()->comment('1 es primera vez y 2 es subsecuente');
            $table->string('diagnostico3', 191)->nullable();
            $table->integer('opcion_diagnostico3')->nullable()->comment('1 es primera vez y 2 es subsecuente');
            $table->string('pronostico', 191)->nullable();
            $table->bigInteger('appointment_id')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('doctor_id');
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
        Schema::dropIfExists('referencias');
    }
};
