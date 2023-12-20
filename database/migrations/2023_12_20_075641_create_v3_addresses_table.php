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
        Schema::create('v3_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id')->index('v3_addresses_patient_id_foreign');
            $table->string('state');
            $table->string('city');
            $table->string('postal_code');
            $table->string('colony');
            $table->string('street');
            $table->string('number');
            $table->string('country')->nullable();
            $table->string('municipality')->nullable();
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
        Schema::dropIfExists('v3_addresses');
    }
};
