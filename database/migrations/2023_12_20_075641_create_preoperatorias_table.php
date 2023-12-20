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
        Schema::create('preoperatorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_cirugia')->nullable();
            $table->text('diagnostico')->nullable();
            $table->longText('plan_quirurgico')->nullable();
            $table->text('tipo_intervencion_quirurgica')->nullable();
            $table->text('riesgo_quirurgico')->nullable();
            $table->longText('cuidados_plan_terapeutico')->nullable();
            $table->text('pronostico')->nullable();
            $table->string('medico_cirujano', 191)->nullable();
            $table->string('auxiliar_instrumentista', 191)->nullable();
            $table->string('primer_ayudante', 191)->nullable();
            $table->string('medico_anestesiologo', 191)->nullable();
            $table->string('personal_enfermeria_1', 191)->nullable();
            $table->string('personal_enfermeria_2', 191)->nullable();
            $table->string('personal_enfermeria_3', 191)->nullable();
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
        Schema::dropIfExists('preoperatorias');
    }
};
