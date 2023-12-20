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
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->string('subtitle', 191);
            $table->string('slug', 191)->unique();
            $table->integer('level')->nullable();
            $table->double('cost', 8, 2);
            $table->double('cost_year', 8, 2)->default(0);
            $table->tinyInteger('trial');
            $table->integer('trial_days')->nullable();
            $table->text('options');
            $table->text('description')->nullable();
            $table->boolean('flan')->nullable();
            $table->string('stripe_tax_rate_id')->nullable();
            $table->string('stripe_id')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
