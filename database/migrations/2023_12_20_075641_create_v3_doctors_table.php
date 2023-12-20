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
        Schema::create('v3_doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('v3_doctors_user_id_foreign');
            $table->string('mk_id')->nullable();
            $table->string('title', 10)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone', 10)->nullable();
            $table->string('recommendation', 500)->nullable();
            $table->text('about_me')->nullable();
            $table->boolean('service_office')->default(false);
            $table->boolean('service_videocall')->default(false);
            $table->boolean('service_home')->default(false);
            $table->text('website')->nullable();
            $table->text('tiktok')->nullable();
            $table->text('facebook')->nullable();
            $table->text('instagram')->nullable();
            $table->text('twitter')->nullable();
            $table->string('tax_email')->nullable();
            $table->text('tax_cer_info')->nullable();
            $table->dateTime('tax_cer_valid')->nullable();
            $table->string('tax_cer_serie')->nullable();
            $table->string('tax_uso_cfdi')->nullable();
            $table->string('tax_razon_social')->nullable();
            $table->string('tax_rfc')->nullable();
            $table->string('tax_cer_password')->nullable();
            $table->string('tax_postal_code')->nullable();
            $table->string('tax_regime_code')->nullable();
            $table->boolean('stripe_cupon_used')->default(false);
            $table->timestamps();
            $table->string('tax_postal_code_for_customers')->nullable();
            $table->string('regime_code_for_customers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_doctors');
    }
};
