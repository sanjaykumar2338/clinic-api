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
        Schema::create('v3_appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date')->nullable();
            $table->string('status')->nullable();
            $table->integer('slots')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index('v3_appointments_created_by_foreign');
            $table->unsignedBigInteger('patient_id')->nullable()->index('v3_appointments_patient_id_foreign');
            $table->unsignedBigInteger('doctor_id')->nullable()->index('v3_appointments_doctor_id_foreign');
            $table->unsignedBigInteger('office_id')->nullable()->index('v3_appointments_office_id_foreign');
            $table->unsignedBigInteger('service_id')->nullable()->index('v3_appointments_service_id_foreign');
            $table->string('mk_id')->nullable();
            $table->text('google_maps_object')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('ended_by_doctor')->nullable();
            $table->boolean('ended_by_patient')->nullable();
            $table->decimal('amount', 10)->default(0);
            $table->decimal('fee', 10)->default(0);
            $table->decimal('total_amount', 10)->default(0);
            $table->string('invoice_pdf')->nullable();
            $table->string('invoice_xml')->nullable();
            $table->timestamps();
            $table->string('stripe_payment_id')->nullable();
            $table->string('stripe_payment_url')->nullable();
            $table->string('stripe_payment_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_appointments');
    }
};
