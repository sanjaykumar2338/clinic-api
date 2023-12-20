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
        Schema::create('postoperatorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('diagnostico_preoperatorio', 191)->nullable();
            $table->string('operacion_planeada', 191)->nullable();
            $table->string('operacion_realizada', 191)->nullable();
            $table->string('diagnostico_postoperatorio', 191)->nullable();
            $table->string('descripcion_tecnica_quirurgica', 191)->nullable();
            $table->string('hallazgos_transoperatorios', 191)->nullable();
            $table->string('reporte_conteo_gasas', 191)->nullable();
            $table->string('compresas', 191)->nullable();
            $table->string('instrumental_quirurgico', 191)->nullable();
            $table->string('incidentes_accidentes', 191)->nullable();
            $table->integer('si_hubo')->nullable();
            $table->string('cuantificacion_sangrado', 191)->nullable();
            $table->string('transfusiones', 191)->nullable();
            $table->string('estudios_servicios_aux_diag_trat_tran', 191)->nullable();
            $table->string('medico_cirujano', 191)->nullable();
            $table->string('auxiliar_instrumentista', 191)->nullable();
            $table->string('primer_ayudante', 191)->nullable();
            $table->string('medico_anestesiologo', 191)->nullable();
            $table->string('personal_enfermeria_1', 191)->nullable();
            $table->string('personal_enfermeria_2', 191)->nullable();
            $table->string('personal_enfermeria_3', 191)->nullable();
            $table->string('estado_post_quirurgico_inmediato', 191)->nullable();
            $table->string('plan_postoperatorio_inmediato', 191)->nullable();
            $table->string('pronostico', 191)->nullable();
            $table->string('biopsias_quirurgicas_histopatologico', 191)->nullable();
            $table->string('hallazgos_paciente_medico', 191)->nullable();
            $table->string('nombre_completo_firma_responsable_cirugia', 191)->nullable();
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
        Schema::dropIfExists('postoperatorias');
    }
};
