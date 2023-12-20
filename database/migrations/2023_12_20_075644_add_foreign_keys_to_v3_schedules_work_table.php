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
        Schema::table('v3_schedules_work', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('v3_doctors')->onDelete('CASCADE');
            $table->foreign(['office_id'])->references(['id'])->on('v3_doctor_offices')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_schedules_work', function (Blueprint $table) {
            $table->dropForeign('v3_schedules_work_doctor_id_foreign');
            $table->dropForeign('v3_schedules_work_office_id_foreign');
        });
    }
};
