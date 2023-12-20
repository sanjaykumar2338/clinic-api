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
        Schema::table('favorite_doctors', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('users');
            $table->foreign(['patient_id'])->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorite_doctors', function (Blueprint $table) {
            $table->dropForeign('favorite_doctors_doctor_id_foreign');
            $table->dropForeign('favorite_doctors_patient_id_foreign');
        });
    }
};
