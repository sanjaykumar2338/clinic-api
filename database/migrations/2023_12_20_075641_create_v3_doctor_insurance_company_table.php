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
        Schema::create('v3_doctor_insurance_company', function (Blueprint $table) {
            $table->unsignedBigInteger('v3_doctor_id')->nullable()->index('v3_doctor_insurance_company_v3_doctor_id_foreign');
            $table->unsignedBigInteger('v3_insurance_company_id')->nullable()->index('v3_doctor_insurance_company_v3_insurance_company_id_foreign');
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
        Schema::dropIfExists('v3_doctor_insurance_company');
    }
};
