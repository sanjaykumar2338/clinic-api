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
        Schema::table('archivos_laboratorio_interconsultas', function (Blueprint $table) {
            $table->foreign(['appointment_id'])->references(['id'])->on('appointments');
            $table->foreign(['interconsulta_id'])->references(['id'])->on('interconsultas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivos_laboratorio_interconsultas', function (Blueprint $table) {
            $table->dropForeign('archivos_laboratorio_interconsultas_appointment_id_foreign');
            $table->dropForeign('archivos_laboratorio_interconsultas_interconsulta_id_foreign');
        });
    }
};
