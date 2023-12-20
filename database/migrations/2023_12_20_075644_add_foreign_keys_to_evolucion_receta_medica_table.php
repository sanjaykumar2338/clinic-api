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
        Schema::table('evolucion_receta_medica', function (Blueprint $table) {
            $table->foreign(['evolucion_id'])->references(['id'])->on('evoluciones')->onDelete('CASCADE');
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
        Schema::table('evolucion_receta_medica', function (Blueprint $table) {
            $table->dropForeign('evolucion_receta_medica_evolucion_id_foreign');
            $table->dropForeign('evolucion_receta_medica_receta_medica_id_foreign');
        });
    }
};
