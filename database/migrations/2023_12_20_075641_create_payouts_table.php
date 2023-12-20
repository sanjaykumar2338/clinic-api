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
        Schema::create('payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount', 191);
            $table->string('payment_method', 191);
            $table->string('currency', 191);
            $table->string('status', 191);
            $table->text('payout_detail')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index('payouts_user_id_foreign');
            $table->unsignedBigInteger('order_id')->index('payouts_order_id_foreign');
            $table->text('product_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }
};
