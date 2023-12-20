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
        Schema::create('v3_doctor_patient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id')->index('v3_doctor_patient_doctor_id_foreign');
            $table->unsignedBigInteger('patient_id')->index('v3_doctor_patient_patient_id_foreign');
            $table->string('expedient_id')->nullable();
            $table->json('ref_note')->nullable();
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
        Schema::dropIfExists('v3_doctor_patient');
    }
};
