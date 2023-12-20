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
        Schema::table('v3_doctor_offices', function (Blueprint $table) {
            $table->foreign(['v3_doctor_id'])->references(['id'])->on('v3_doctors');
            $table->foreign(['v3_hospital_id'])->references(['id'])->on('v3_hospitals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_doctor_offices', function (Blueprint $table) {
            $table->dropForeign('v3_doctor_offices_v3_doctor_id_foreign');
            $table->dropForeign('v3_doctor_offices_v3_hospital_id_foreign');
        });
    }
};
