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
        Schema::create('v3_appointments_details_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('lab');
            $table->string('name');
            $table->string('path');
            $table->unsignedBigInteger('appointment_details_id')->nullable()->index('v3_appointments_details_files_appointment_details_id_foreign');
            $table->unsignedBigInteger('doctor_id')->index('v3_appointments_details_files_doctor_id_foreign');
            $table->unsignedBigInteger('patient_id')->index('v3_appointments_details_files_patient_id_foreign');
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
        Schema::dropIfExists('v3_appointments_details_files');
    }
};
