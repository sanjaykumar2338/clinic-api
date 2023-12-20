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
        Schema::create('receta_medicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paciente_id')->nullable()->index('receta_medicas_paciente_id_foreign');
            $table->string('product_uuid', 191)->nullable();
            $table->string('product_name', 191);
            $table->string('laboratorio', 191)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('dosis', 191)->nullable();
            $table->string('peso', 191)->nullable();
            $table->string('talla', 191)->nullable();
            $table->string('temperatura', 191)->nullable();
            $table->string('fecha', 191)->nullable();
            $table->string('via_administracion', 191)->nullable();
            $table->string('duracion', 191)->nullable();
            $table->boolean('sin_suspender')->nullable();
            $table->text('abierto')->nullable()->comment('Columna para colocar datos cualquiera es decir es un campo abierto');
            $table->text('indicaciones')->nullable()->comment('Columna para indicaciones en la receta medica');
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
        Schema::dropIfExists('receta_medicas');
    }
};
