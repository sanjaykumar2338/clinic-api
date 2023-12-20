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
        Schema::create('memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('memberships_user_id_foreign');
            $table->unsignedInteger('package_id')->index('memberships_package_id_foreign');
            $table->unsignedBigInteger('package_price_id')->nullable()->index('memberships_package_price_id_foreign');
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->boolean('r_invoice')->default(false);
            $table->dateTime('invoiced_at')->nullable();
            $table->string('invoice_xml', 250)->nullable();
            $table->string('invoice_pdf', 250)->nullable();
            $table->string('conekta_order', 191)->nullable();
            $table->string('stripe_status')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->text('stripe_subscription_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberships');
    }
};
