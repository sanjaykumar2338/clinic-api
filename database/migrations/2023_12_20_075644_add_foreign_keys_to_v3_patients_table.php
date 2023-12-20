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
        Schema::table('v3_patients', function (Blueprint $table) {
            $table->foreign(['patient_id'], 'patients_patient_id_foreign')->references(['id'])->on('users');
            $table->foreign(['user_id'], 'patients_user_id_foreign')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_patients', function (Blueprint $table) {
            $table->dropForeign('patients_patient_id_foreign');
            $table->dropForeign('patients_user_id_foreign');
        });
    }
};
