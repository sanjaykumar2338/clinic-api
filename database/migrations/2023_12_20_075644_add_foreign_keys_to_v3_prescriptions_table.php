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
        Schema::table('v3_prescriptions', function (Blueprint $table) {
            $table->foreign(['appointment_details_id'])->references(['id'])->on('v3_appointments_details');
            $table->foreign(['patient_id'])->references(['id'])->on('v3_patients');
            $table->foreign(['doctor_id'])->references(['id'])->on('v3_doctors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_prescriptions', function (Blueprint $table) {
            $table->dropForeign('v3_prescriptions_appointment_details_id_foreign');
            $table->dropForeign('v3_prescriptions_patient_id_foreign');
            $table->dropForeign('v3_prescriptions_doctor_id_foreign');
        });
    }
};
