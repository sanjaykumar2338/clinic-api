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
        Schema::table('archivo_lab_interconsulta_sin_cuentas', function (Blueprint $table) {
            $table->foreign(['interconsulta_id'])->references(['id'])->on('interconsultas');
            $table->foreign(['user_id'])->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivo_lab_interconsulta_sin_cuentas', function (Blueprint $table) {
            $table->dropForeign('archivo_lab_interconsulta_sin_cuentas_interconsulta_id_foreign');
            $table->dropForeign('archivo_lab_interconsulta_sin_cuentas_user_id_foreign');
        });
    }
};
