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
        Schema::create('v3_doctor_speciality', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_doctor_id');
            $table->unsignedBigInteger('v3_speciality_id')->index('v3_doctor_speciality_v3_speciality_id_foreign');
            $table->string('license', 500)->nullable();
            $table->string('title', 500)->nullable();
            $table->text('description')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->timestamps();

            $table->unique(['v3_doctor_id', 'v3_speciality_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_doctor_speciality');
    }
};
