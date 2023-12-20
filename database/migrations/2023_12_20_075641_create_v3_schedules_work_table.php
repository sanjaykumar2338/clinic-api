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
        Schema::create('v3_schedules_work', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('day_week');
            $table->boolean('day_status')->default(true);
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->integer('spaces')->default(1);
            $table->unsignedBigInteger('office_id')->nullable()->index('v3_schedules_work_office_id_foreign');
            $table->unsignedBigInteger('doctor_id')->nullable()->index('v3_schedules_work_doctor_id_foreign');
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
        Schema::dropIfExists('v3_schedules_work');
    }
};
