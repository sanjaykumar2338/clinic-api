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
        Schema::create('usuario_nutricion_notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('peso', 191)->nullable();
            $table->string('altura', 191)->nullable();
            $table->string('pierna_i', 191)->nullable();
            $table->string('pierna_d', 191)->nullable();
            $table->string('brazo_i', 191)->nullable();
            $table->string('brazo_d', 191)->nullable();
            $table->string('pompa', 191)->nullable();
            $table->string('cintura', 191)->nullable();
            $table->string('panza', 191)->nullable();
            $table->string('pecho', 191)->nullable();
            $table->string('imc', 191)->nullable();
            $table->integer('ejercicio_semana')->nullable();
            $table->integer('ejercicio_dia')->nullable();
            $table->text('nota')->nullable();
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
        Schema::dropIfExists('usuario_nutricion_notas');
    }
};
