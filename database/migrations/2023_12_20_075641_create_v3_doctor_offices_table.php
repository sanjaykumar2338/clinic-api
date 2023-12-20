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
        Schema::create('v3_doctor_offices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_doctor_id')->nullable()->index('v3_doctor_offices_v3_doctor_id_foreign');
            $table->unsignedBigInteger('v3_hospital_id')->nullable()->index('v3_doctor_offices_v3_hospital_id_foreign');
            $table->string('type')->default('office');
            $table->text('google_maps_object')->nullable();
            $table->string('name')->nullable();
            $table->text('phones')->nullable();
            $table->string('max_distance')->nullable();
            $table->boolean('is_default')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_doctor_offices');
    }
};
