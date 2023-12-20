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
        Schema::create('v3_doctor_degrees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_doctor_id')->nullable()->index('v3_doctor_degrees_v3_doctor_id_foreign');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->date('start')->nullable();
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
        Schema::dropIfExists('v3_doctor_degrees');
    }
};
