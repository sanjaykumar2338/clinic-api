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
        Schema::create('v3_medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('medication_code')->index();
            $table->string('brand')->nullable();
            $table->string('name');
            $table->string('laboratory')->nullable();
            $table->string('generic_name')->nullable();
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
        Schema::dropIfExists('v3_medicines');
    }
};
