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
        Schema::create('notificacion_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paciente_id')->nullable()->index('notificacion_pacientes_paciente_id_foreign');
            $table->unsignedBigInteger('doctor_id')->nullable()->index('notificacion_pacientes_doctor_id_foreign');
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->string('titulo', 191)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('enviado')->nullable()->comment('0 es no enviado, 1 es enviado');
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
        Schema::dropIfExists('notificacion_pacientes');
    }
};
