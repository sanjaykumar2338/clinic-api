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
        Schema::create('mcl_expenses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('patient')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->string('payment_purpose')->nullable();
            $table->integer('category')->nullable();
            $table->integer('provider')->nullable();
            $table->double('cost', 100, 2)->nullable();
            $table->double('amount', 100, 2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->text('comments')->nullable();
            $table->double('paid', 100, 2)->nullable();
            $table->double('to_be_paid', 100, 2)->nullable();
            $table->boolean('status')->default(false);
            $table->integer('quantity')->nullable();
            $table->string('type', 100)->default('expenses');
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
        Schema::dropIfExists('mcl_expenses');
    }
};
