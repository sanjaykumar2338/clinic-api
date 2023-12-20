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
        Schema::create('mcl_billing_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('clinic_id')->nullable();
            $table->string('name')->nullable();
            $table->string('rfc')->nullable();
            $table->string('use_of_invoice')->nullable();
            $table->string('fiscal_regime')->nullable();
            $table->string('email')->nullable();
            $table->string('email_2')->nullable();
            $table->string('email_3')->nullable();
            $table->string('postal_code')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcl_billing_details');
    }
};
