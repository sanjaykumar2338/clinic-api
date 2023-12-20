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
        Schema::create('v3_doctor_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_doctor_id')->nullable()->index('v3_doctor_services_v3_doctor_id_foreign');
            $table->unsignedBigInteger('v3_speciality_id')->nullable()->index('v3_doctor_services_v3_speciality_id_foreign');
            $table->unsignedBigInteger('v3_service_id')->nullable()->index('v3_doctor_services_v3_service_id_foreign');
            $table->string('name')->nullable();
            $table->decimal('cost_office', 10)->nullable();
            $table->decimal('cost_videocall', 10)->nullable();
            $table->decimal('cost_home', 10)->nullable();
            $table->integer('time_home')->default(0);
            $table->integer('time_videocall')->default(0);
            $table->integer('time_office')->default(0);
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
        Schema::dropIfExists('v3_doctor_services');
    }
};
