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
        Schema::table('stripe_payments', function (Blueprint $table) {
            $table->foreign(['appointment_id'])->references(['id'])->on('appointments');
            $table->foreign(['patient_id'])->references(['id'])->on('users');
            $table->foreign(['doctor_id'])->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_payments', function (Blueprint $table) {
            $table->dropForeign('stripe_payments_appointment_id_foreign');
            $table->dropForeign('stripe_payments_patient_id_foreign');
            $table->dropForeign('stripe_payments_doctor_id_foreign');
        });
    }
};
