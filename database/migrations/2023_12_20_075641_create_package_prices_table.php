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
        Schema::create('package_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('package_id')->index('package_prices_package_id_foreign');
            $table->unsignedBigInteger('package_period_id')->index('package_prices_package_period_id_foreign');
            $table->decimal('price', 10)->default(0);
            $table->tinyInteger('period_limit')->default(0);
            $table->string('stripe_id')->nullable();
            $table->integer('trial_days')->nullable();
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
        Schema::dropIfExists('package_prices');
    }
};
