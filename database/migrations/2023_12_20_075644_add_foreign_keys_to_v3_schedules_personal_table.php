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
        Schema::table('v3_schedules_personal', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('v3_doctors')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_schedules_personal', function (Blueprint $table) {
            $table->dropForeign('v3_schedules_personal_doctor_id_foreign');
        });
    }
};
