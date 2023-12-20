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
        Schema::create('mcl_revenue', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('patient')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->dateTime('date')->nullable()->useCurrent();
            $table->integer('doctor')->nullable();
            $table->integer('payment_purpose')->nullable();
            $table->double('price', 100, 2)->nullable();
            $table->double('amount_paid', 100, 2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->text('comments')->nullable();
            $table->integer('inventory')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('type', 100)->default('revenue');
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
        Schema::dropIfExists('mcl_revenue');
    }
};
