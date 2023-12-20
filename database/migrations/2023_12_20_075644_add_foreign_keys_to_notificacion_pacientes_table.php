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
        Schema::table('notificacion_pacientes', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['paciente_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notificacion_pacientes', function (Blueprint $table) {
            $table->dropForeign('notificacion_pacientes_doctor_id_foreign');
            $table->dropForeign('notificacion_pacientes_paciente_id_foreign');
        });
    }
};
