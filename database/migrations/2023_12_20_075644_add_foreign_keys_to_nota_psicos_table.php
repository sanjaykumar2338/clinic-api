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
        Schema::table('nota_psicos', function (Blueprint $table) {
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
        Schema::table('nota_psicos', function (Blueprint $table) {
            $table->dropForeign('nota_psicos_doctor_id_foreign');
            $table->dropForeign('nota_psicos_paciente_id_foreign');
        });
    }
};
