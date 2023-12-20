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
        Schema::create('historias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha')->nullable();
            $table->string('hora', 191)->nullable();
            $table->string('um', 191)->nullable();
            $table->string('expediente', 191)->nullable();
            $table->string('lugar', 191)->nullable();
            $table->string('tipo', 191)->nullable();
            $table->string('nombre_relacion_familiar', 191)->nullable();
            $table->string('relacion_familiar', 191)->nullable();
            $table->string('contacto_relacion_familiar', 191)->nullable();
            $table->string('familiar_responsable', 191)->nullable();
            $table->string('paciente', 191)->nullable();
            $table->string('edad', 191)->nullable();
            $table->integer('genero')->nullable()->comment('1-masculino, 2-femenino, 3-prefiero no especificar, 4-otro');
            $table->string('otro_genero', 191)->nullable()->comment('cuando se seleccionno la opción 4 de genero');
            $table->date('fechanacimiento')->nullable();
            $table->string('ocupacion', 191)->nullable();
            $table->string('domicilio', 191)->nullable();
            $table->string('telefono', 191)->nullable();
            $table->integer('estado_civil')->nullable()->comment('1-Soltera(o), 2-Casada(o), 3-Unión libre, 4-Viuda(o)');
            $table->string('origen', 191)->nullable();
            $table->integer('escolaridad')->nullable()->comment('1-ninguna, 2-primaria, 3-secundaria, 4-preparatoria, 5-preparatoria incompleta, 6-bachillerato, 7-carrera tecnica, 8-licenciatura, 9-licenciatura incompleta, 10-maestria, 11-doctorado');
            $table->longText('ingreso')->nullable();
            $table->longText('antecedentesfamiliares')->nullable();
            $table->longText('antecedentesnopatologicos')->nullable();
            $table->string('gruposanguineo', 191)->nullable();
            $table->longText('antecedentespatologicos')->nullable();
            $table->string('alergicos', 191)->nullable();
            $table->longText('antecedentesgineco')->nullable();
            $table->string('fum', 191)->nullable();
            $table->string('fpp', 191)->nullable();
            $table->string('g', 191)->nullable();
            $table->string('p', 191)->nullable();
            $table->string('a', 191)->nullable();
            $table->string('c', 191)->nullable();
            $table->string('sdg', 191)->nullable();
            $table->string('cardiovascular', 191)->nullable();
            $table->string('respiratorio', 191)->nullable();
            $table->string('gastrointestinal', 191)->nullable();
            $table->string('genitourinario', 191)->nullable();
            $table->string('hematico', 191)->nullable();
            $table->string('endocrino', 191)->nullable();
            $table->string('nervioso', 191)->nullable();
            $table->string('musculoesqueletico', 191)->nullable();
            $table->string('anexos', 191)->nullable();
            $table->string('ta', 191)->nullable();
            $table->string('temp', 191)->nullable();
            $table->string('frec', 191)->nullable();
            $table->string('peso', 191)->nullable();
            $table->string('talla', 191)->nullable();
            $table->string('fcf', 191)->nullable();
            $table->string('frec_card', 191)->nullable();
            $table->string('saturacion', 191)->nullable();
            $table->string('habitusexterior', 191)->nullable();
            $table->string('cabeza', 191)->nullable();
            $table->string('cuello', 191)->nullable();
            $table->string('torax', 191)->nullable();
            $table->string('abdomen', 191)->nullable();
            $table->string('genitales', 191)->nullable();
            $table->string('extremidades', 191)->nullable();
            $table->string('piel', 191)->nullable();
            $table->string('diagnosticomotivo', 191)->nullable();
            $table->string('diagnostico2', 191)->nullable();
            $table->string('opcion_diagnostico2', 191)->nullable();
            $table->string('diagnostico3', 191)->nullable();
            $table->string('opcion_diagnostico3', 191)->nullable();
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
        Schema::dropIfExists('historias');
    }
};
