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
        Schema::create('v3_prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->string('category')->default('Consulta Medica');
            $table->dateTime('start_date')->useCurrent();
            $table->dateTime('end_date')->useCurrent();
            $table->string('mk_id')->nullable();
            $table->unsignedBigInteger('patient_id')->index('v3_prescriptions_patient_id_foreign');
            $table->unsignedBigInteger('doctor_id')->index('v3_prescriptions_doctor_id_foreign');
            $table->unsignedBigInteger('appointment_details_id')->nullable()->index('v3_prescriptions_appointment_details_id_foreign');
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
        Schema::dropIfExists('v3_prescriptions');
    }
};
