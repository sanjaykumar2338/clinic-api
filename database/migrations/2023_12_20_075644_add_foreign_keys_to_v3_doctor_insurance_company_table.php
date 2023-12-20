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
        Schema::table('v3_doctor_insurance_company', function (Blueprint $table) {
            $table->foreign(['v3_doctor_id'])->references(['id'])->on('v3_doctors');
            $table->foreign(['v3_insurance_company_id'])->references(['id'])->on('v3_insurance_companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_doctor_insurance_company', function (Blueprint $table) {
            $table->dropForeign('v3_doctor_insurance_company_v3_doctor_id_foreign');
            $table->dropForeign('v3_doctor_insurance_company_v3_insurance_company_id_foreign');
        });
    }
};
