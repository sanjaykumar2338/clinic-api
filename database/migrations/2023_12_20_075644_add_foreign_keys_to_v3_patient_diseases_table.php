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
        Schema::table('v3_patient_diseases', function (Blueprint $table) {
            $table->foreign(['disease_id'])->references(['id'])->on('v3_speciality_diseases');
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
        Schema::table('v3_patient_diseases', function (Blueprint $table) {
            $table->dropForeign('v3_patient_diseases_disease_id_foreign');
            $table->dropForeign('v3_patient_diseases_patient_id_foreign');
            $table->dropForeign('v3_patient_diseases_doctor_id_foreign');
        });
    }
};
