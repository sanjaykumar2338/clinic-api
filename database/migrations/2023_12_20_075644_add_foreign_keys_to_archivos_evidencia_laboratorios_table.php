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
        Schema::table('archivos_evidencia_laboratorios', function (Blueprint $table) {
            $table->foreign(['appointment_id'])->references(['id'])->on('appointments');
            $table->foreign(['evoluciones_id'])->references(['id'])->on('evoluciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivos_evidencia_laboratorios', function (Blueprint $table) {
            $table->dropForeign('archivos_evidencia_laboratorios_appointment_id_foreign');
            $table->dropForeign('archivos_evidencia_laboratorios_evoluciones_id_foreign');
        });
    }
};
