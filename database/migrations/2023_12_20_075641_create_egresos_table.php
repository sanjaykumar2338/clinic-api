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
        Schema::create('egresos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_egreso')->nullable();
            $table->text('motivo_egreso')->nullable();
            $table->longText('diagnosticos_finales')->nullable();
            $table->longText('resumen_evolucion')->nullable();
            $table->text('estado_actual')->nullable();
            $table->text('manejo_estancia_hospitalaria')->nullable();
            $table->text('problemas_clinicos_pendientes')->nullable();
            $table->text('plan_manejo')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('recomendaciones_vigilancia_ambulatoria')->nullable();
            $table->text('atencion_factores_riesgo')->nullable();
            $table->text('pronostico')->nullable();
            $table->integer('alta')->nullable()->comment('1 es alta por mejoria, 2 alta por defuncion');
            $table->string('causa_muerte', 191)->nullable();
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
        Schema::dropIfExists('egresos');
    }
};
