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
        Schema::table('notificacion_archivos', function (Blueprint $table) {
            $table->foreign(['notificacion_id'])->references(['id'])->on('notificacion_pacientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notificacion_archivos', function (Blueprint $table) {
            $table->dropForeign('notificacion_archivos_notificacion_id_foreign');
        });
    }
};
