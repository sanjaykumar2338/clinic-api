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
        Schema::create('detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 191)->nullable();
            $table->string('apellidos', 191)->nullable();
            $table->integer('edad')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('genero')->nullable()->comment('1-masculino, 2-femenino, 3-prefiero no especificar, 4-otro');
            $table->string('otro_genero', 191)->nullable()->comment('cuando se seleccionno la opción 4 de genero');
            $table->integer('escolaridad')->nullable()->comment('1-ninguna, 2-primaria, 3-secundaria, 4-preparatoria, 5-preparatoria incompleta, 6-bachillerato, 7-carrera tecnica, 8-licenciatura, 9-licenciatura incompleta, 10-maestria, 11-doctorado');
            $table->integer('religion')->nullable()->comment('1-judaismo, 2-cristianismo, 3-islam, 4-budismo, 5-testigos de jehova, 6-otro');
            $table->string('otro_religion', 191)->nullable()->comment('cuando se seleccionno la opción 6 de religion');
            $table->string('ocupacion', 191)->nullable();
            $table->integer('estado_civil')->nullable()->comment('1-Soltera(o), 2-Casada(o), 3-Unión libre, 4-Viuda(o)');
            $table->string('telefono', 191)->nullable();
            $table->string('curp', 191)->nullable();
            $table->string('diabetes', 191)->nullable()->default('No');
            $table->string('hipertension', 191)->nullable()->default('No');
            $table->string('cardiovascular', 191)->nullable()->default('No');
            $table->string('renal', 191)->nullable()->default('No');
            $table->string('hepatica', 191)->nullable()->default('No');
            $table->string('cancer', 191)->nullable()->default('No');
            $table->string('enfermedad', 191)->nullable()->default('No');
            $table->string('enfermedad2', 191)->nullable();
            $table->string('alergia', 191)->nullable()->default('No');
            $table->string('alergia2', 191)->nullable();
            $table->string('operacion', 191)->nullable()->default('No');
            $table->string('operacion2', 191)->nullable();
            $table->string('transfusion', 191)->nullable()->default('No');
            $table->string('transfusion2', 191)->nullable();
            $table->string('medicamento', 191)->nullable()->default('No');
            $table->string('medicamento2', 191)->nullable();
            $table->string('menarca', 191)->nullable();
            $table->string('ciclo', 191)->nullable();
            $table->string('duracion', 191)->nullable();
            $table->string('periodo', 191)->nullable();
            $table->string('parejas', 191)->nullable();
            $table->string('embarazos', 191)->nullable();
            $table->string('cesarea', 191)->nullable()->default('No');
            $table->string('parto', 191)->nullable();
            $table->string('planificacion', 191)->nullable()->default('No');
            $table->string('planificacion2', 191)->nullable();
            $table->string('ets', 191)->nullable()->default('No');
            $table->string('ets2', 191)->nullable();
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('detalles');
    }
};
