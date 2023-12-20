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
        Schema::table('interconsulta_receta_medica', function (Blueprint $table) {
            $table->foreign(['interconsulta_id'])->references(['id'])->on('interconsultas')->onDelete('CASCADE');
            $table->foreign(['receta_medica_id'])->references(['id'])->on('receta_medicas')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interconsulta_receta_medica', function (Blueprint $table) {
            $table->dropForeign('interconsulta_receta_medica_interconsulta_id_foreign');
            $table->dropForeign('interconsulta_receta_medica_receta_medica_id_foreign');
        });
    }
};
