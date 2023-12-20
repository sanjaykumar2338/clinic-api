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
        Schema::create('plan_dietas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dieta_id');
            $table->string('color_celda', 191)->nullable();
            $table->string('tiempo_comida', 191)->nullable();
            $table->string('hora_inicio', 191)->nullable();
            $table->string('hora_fin', 191)->nullable();
            $table->text('lunes')->nullable();
            $table->text('martes')->nullable();
            $table->text('miercoles')->nullable();
            $table->text('jueves')->nullable();
            $table->text('viernes')->nullable();
            $table->text('sabado')->nullable();
            $table->text('domingo')->nullable();
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
        Schema::dropIfExists('plan_dietas');
    }
};
