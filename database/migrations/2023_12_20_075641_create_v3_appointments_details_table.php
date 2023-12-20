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
        Schema::create('v3_appointments_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->string('type')->default('first_time');
            $table->double('ta', 8, 2)->default(0);
            $table->double('temp', 8, 2)->default(0);
            $table->double('fc', 8, 2)->default(0);
            $table->double('weight', 8, 2)->default(0);
            $table->double('size', 8, 2)->default(0);
            $table->dateTime('time_close')->nullable();
            $table->string('place')->nullable();
            $table->longText('current_condition')->nullable();
            $table->string('type_interrogation')->nullable();
            $table->string('q_cardiovascular')->nullable();
            $table->string('q_respiratory')->nullable();
            $table->string('q_gastrointestinal')->nullable();
            $table->string('q_genitourinary')->nullable();
            $table->string('q_nervous')->nullable();
            $table->string('q_skeletal_muscle')->nullable();
            $table->string('q_hematic_lymphatic')->nullable();
            $table->string('q_endocrine')->nullable();
            $table->string('f_habits')->nullable();
            $table->string('f_head')->nullable();
            $table->string('f_neck')->nullable();
            $table->string('f_chest')->nullable();
            $table->string('f_abdomen')->nullable();
            $table->string('f_genitals')->nullable();
            $table->string('f_extremities')->nullable();
            $table->string('f_skin')->nullable();
            $table->json('diagnostics')->nullable();
            $table->longText('summary')->nullable();
            $table->unsignedBigInteger('appointment_id')->nullable()->index('v3_appointments_details_appointment_id_foreign');
            $table->unsignedBigInteger('doctor_id')->index('v3_appointments_details_doctor_id_foreign');
            $table->unsignedBigInteger('patient_id')->index('v3_appointments_details_patient_id_foreign');
            $table->text('free_text')->nullable();
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
        Schema::dropIfExists('v3_appointments_details');
    }
};
