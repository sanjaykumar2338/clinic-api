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
        Schema::create('horarios_apartados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 191);
            $table->string('apellido_paterno', 191);
            $table->string('apellido_materno', 191);
            $table->string('telefono', 191)->nullable();
            $table->string('correo', 191)->nullable();
            $table->date('fecha_cita')->nullable();
            $table->string('hora_cita', 191)->nullable();
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
        Schema::dropIfExists('horarios_apartados');
    }
};
