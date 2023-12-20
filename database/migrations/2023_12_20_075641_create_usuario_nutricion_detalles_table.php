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
        Schema::create('usuario_nutricion_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->string('nombre_paciente', 191)->nullable();
            $table->string('fecha_nacimiento', 191)->nullable();
            $table->string('estatura', 191)->nullable();
            $table->string('peso_inicial', 191)->nullable();
            $table->string('telefono', 191)->nullable();
            $table->string('correo', 191)->nullable();
            $table->integer('edad')->nullable();
            $table->boolean('diarrea')->default(false);
            $table->boolean('estrenimiento')->default(false);
            $table->boolean('gastritis')->default(false);
            $table->boolean('antecedente_ulcera_gastica')->default(false);
            $table->boolean('nauseas_frecuentes')->default(false);
            $table->boolean('vomito_frecuente')->default(false);
            $table->boolean('colitis')->default(false);
            $table->boolean('problemas_dentales')->default(false);
            $table->boolean('problemas_pasar_comida')->default(false);
            $table->text('nota_1')->nullable();
            $table->boolean('hipertension_arterial_1')->default(false);
            $table->boolean('diabetes_1')->default(false);
            $table->boolean('enfermedad_renal')->default(false);
            $table->boolean('hipertiroidismo_1')->default(false);
            $table->boolean('enfermedad_reumatica')->default(false);
            $table->boolean('enfermedad_gastrointestinal_clinca')->default(false);
            $table->boolean('cancer_1')->default(false);
            $table->boolean('hipercolesterolhemia_1')->default(false);
            $table->boolean('hipertrigliceridemia_1')->default(false);
            $table->text('nota_2')->nullable();
            $table->boolean('toma_medicamento')->default(false);
            $table->string('cada_cuanto', 191)->nullable();
            $table->string('desde_cuando', 191)->nullable();
            $table->boolean('laxantes')->default(false);
            $table->boolean('diureticos')->default(false);
            $table->boolean('antiacidos')->default(false);
            $table->boolean('analgesicos')->default(false);
            $table->boolean('anticonceptivos')->default(false);
            $table->text('nota_3')->nullable();
            $table->boolean('obesidad')->default(false);
            $table->boolean('diabetes_2')->default(false);
            $table->boolean('hipertension_arterial_2')->default(false);
            $table->boolean('cancer_2')->default(false);
            $table->boolean('hipercolesterolhemia_2')->default(false);
            $table->boolean('hipertrigliceridemia_2')->default(false);
            $table->boolean('hipertiroidismo_2')->default(false);
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
        Schema::dropIfExists('usuario_nutricion_detalles');
    }
};
