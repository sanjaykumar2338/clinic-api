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
        Schema::create('v3_speciality_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_speciality_id')->nullable()->index('v3_speciality_services_v3_speciality_id_foreign');
            $table->boolean('is_for_office')->default(true);
            $table->boolean('is_for_videocall')->default(true);
            $table->boolean('is_for_home')->default(true);
            $table->string('name');
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('v3_speciality_services');
    }
};
