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
        Schema::create('v3_patient_diseases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('personal');
            $table->string('disease_name', 250)->nullable();
            $table->string('relation')->nullable();
            $table->unsignedBigInteger('patient_id')->index('v3_patient_diseases_patient_id_foreign');
            $table->unsignedBigInteger('doctor_id')->index('v3_patient_diseases_doctor_id_foreign');
            $table->unsignedBigInteger('disease_id')->nullable()->index('v3_patient_diseases_disease_id_foreign');
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
        Schema::dropIfExists('v3_patient_diseases');
    }
};
