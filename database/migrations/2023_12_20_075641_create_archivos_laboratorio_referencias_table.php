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
        Schema::create('archivos_laboratorio_referencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id')->index('archivos_laboratorio_referencias_appointment_id_foreign');
            $table->unsignedBigInteger('referencia_id')->index('archivos_laboratorio_referencias_referencia_id_foreign');
            $table->text('archivo');
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
        Schema::dropIfExists('archivos_laboratorio_referencias');
    }
};
