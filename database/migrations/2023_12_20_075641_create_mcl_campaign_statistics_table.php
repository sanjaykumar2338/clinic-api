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
        Schema::create('mcl_campaign_statistics', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('campaign_id')->nullable();
            $table->date('shipping_date')->nullable();
            $table->integer('sent')->nullable();
            $table->integer('open')->nullable();
            $table->integer('clicks')->nullable();
            $table->integer('generated_appointments')->nullable();
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcl_campaign_statistics');
    }
};
