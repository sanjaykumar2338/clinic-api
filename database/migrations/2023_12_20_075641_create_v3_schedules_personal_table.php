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
        Schema::create('v3_schedules_personal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->time('time_start');
            $table->time('time_end');
            $table->unsignedBigInteger('doctor_id')->index('v3_schedules_personal_doctor_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_schedules_personal');
    }
};
