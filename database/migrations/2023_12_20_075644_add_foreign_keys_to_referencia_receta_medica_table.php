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
        Schema::table('referencia_receta_medica', function (Blueprint $table) {
            $table->foreign(['receta_medica_id'])->references(['id'])->on('receta_medicas')->onDelete('CASCADE');
            $table->foreign(['referencia_id'])->references(['id'])->on('referencias')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referencia_receta_medica', function (Blueprint $table) {
            $table->dropForeign('referencia_receta_medica_receta_medica_id_foreign');
            $table->dropForeign('referencia_receta_medica_referencia_id_foreign');
        });
    }
};
