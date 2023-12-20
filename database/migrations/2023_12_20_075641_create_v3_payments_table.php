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
        Schema::create('v3_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->string('payable_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('v3_payments_user_id_foreign');
            $table->string('type')->nullable();
            $table->text('details')->nullable();
            $table->decimal('amount', 10);
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('v3_payments');
    }
};
