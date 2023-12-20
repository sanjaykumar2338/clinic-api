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
        Schema::table('reunions', function (Blueprint $table) {
            $table->foreign(['appointment_id'])->references(['id'])->on('appointments')->onDelete('CASCADE');
            $table->foreign(['paciente_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['doctor_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reunions', function (Blueprint $table) {
            $table->dropForeign('reunions_appointment_id_foreign');
            $table->dropForeign('reunions_paciente_id_foreign');
            $table->dropForeign('reunions_doctor_id_foreign');
        });
    }
};
