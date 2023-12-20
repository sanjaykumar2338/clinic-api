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
        Schema::create('archivo_lab_evolucion_sin_cuentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('archivo_lab_evolucion_sin_cuentas_user_id_foreign');
            $table->unsignedBigInteger('evoluciones_id')->index('archivo_lab_evolucion_sin_cuentas_evoluciones_id_foreign');
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
        Schema::dropIfExists('archivo_lab_evolucion_sin_cuentas');
    }
};
