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
        Schema::table('v3_appointments', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users');
            $table->foreign(['office_id'])->references(['id'])->on('v3_doctor_offices');
            $table->foreign(['service_id'])->references(['id'])->on('v3_doctor_services');
            $table->foreign(['doctor_id'])->references(['id'])->on('v3_doctors');
            $table->foreign(['patient_id'])->references(['id'])->on('v3_patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_appointments', function (Blueprint $table) {
            $table->dropForeign('v3_appointments_created_by_foreign');
            $table->dropForeign('v3_appointments_office_id_foreign');
            $table->dropForeign('v3_appointments_service_id_foreign');
            $table->dropForeign('v3_appointments_doctor_id_foreign');
            $table->dropForeign('v3_appointments_patient_id_foreign');
        });
    }
};
