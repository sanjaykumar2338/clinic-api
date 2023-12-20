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
        Schema::table('subespecialidad_doctor', function (Blueprint $table) {
            $table->foreign(['sub_speciality_id'])->references(['id'])->on('sub_specialities')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subespecialidad_doctor', function (Blueprint $table) {
            $table->dropForeign('subespecialidad_doctor_sub_speciality_id_foreign');
            $table->dropForeign('subespecialidad_doctor_user_id_foreign');
        });
    }
};
