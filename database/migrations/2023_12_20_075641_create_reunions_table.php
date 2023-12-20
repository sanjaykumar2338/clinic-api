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
        Schema::create('reunions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id')->nullable()->index('reunions_doctor_id_foreign');
            $table->unsignedBigInteger('paciente_id')->nullable()->index('reunions_paciente_id_foreign');
            $table->unsignedBigInteger('appointment_id')->nullable()->index('reunions_appointment_id_foreign');
            $table->string('id_transaccion', 191)->nullable();
            $table->string('room_name', 191)->nullable()->unique();
            $table->date('fecha_reunion')->nullable();
            $table->string('hora_reunion', 191)->nullable();
            $table->boolean('finalizo')->nullable()->default(false);
            $table->string('fecha_finalizo', 191)->nullable();
            $table->string('hora_finalizo', 191)->nullable();
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
        Schema::dropIfExists('reunions');
    }
};
