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
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_id', 191);
            $table->string('payment_amount', 191);
            $table->string('payment_stripe_customer', 191)->nullable();
            $table->string('payment_account_destination', 191)->nullable();
            $table->string('payment_fee', 191)->nullable();
            $table->string('payment_receipt_url')->nullable();
            $table->string('payment_transaction')->nullable();
            $table->string('payment_status', 191)->default('open');
            $table->unsignedBigInteger('doctor_id')->index('stripe_payments_doctor_id_foreign');
            $table->unsignedBigInteger('patient_id')->index('stripe_payments_patient_id_foreign');
            $table->unsignedBigInteger('appointment_id')->index('stripe_payments_appointment_id_foreign');
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
        Schema::dropIfExists('stripe_payments');
    }
};
