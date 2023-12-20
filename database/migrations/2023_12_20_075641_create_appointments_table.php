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
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_appointment_id')->nullable()->index('appointments_v3_appointment_id_foreign');
            $table->integer('user_id');
            $table->integer('hospital_id');
            $table->integer('patient_id')->nullable();
            $table->integer('acceso')->default(0);
            $table->string('patient_name', 191)->nullable();
            $table->enum('relation', ['father', 'mother', 'sister', 'brother', 'friend', 'other'])->nullable();
            $table->text('services')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('es_reserva')->default(false);
            $table->string('appointment_time', 191);
            $table->string('appointment_date', 191);
            $table->integer('charges');
            $table->double('comisiones', 10, 2);
            $table->double('total_pagar', 10, 2);
            $table->boolean('estatus_pago');
            $table->boolean('es_efectivo')->default(false);
            $table->string('status', 191)->default('pending');
            $table->string('tipo', 191)->default('regular');
            $table->boolean('finalizo_doctor')->nullable();
            $table->boolean('finalizo_paciente')->nullable();
            $table->integer('patient_ubicacion_id')->nullable()->comment('id de la ubicación seleccionada por el paciente');
            $table->string('invoice_pdf', 191)->nullable();
            $table->string('invoice_xml', 191)->nullable();
            $table->string('conekta_order', 191)->nullable();
            $table->string('stripe_payment_id', 191)->nullable();
            $table->longText('stripe_payment_url')->nullable();
            $table->string('stripe_payment_status', 191)->nullable();
            $table->string('whocreate', 191)->nullable();
            $table->timestamps();
            $table->enum('type', ['offline', 'online'])->nullable();
            $table->enum('paid', ['pending', 'completed'])->nullable();
            $table->enum('payout_progress', ['processing', 'processed'])->nullable();
            $table->string('other_relation', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
