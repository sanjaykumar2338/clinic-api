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
        Schema::create('v3_prescriptions_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('substitute')->default(false);
            $table->integer('quantity');
            $table->text('instructions');
            $table->integer('duration');
            $table->string('diagnosis')->default('Z759');
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->unsignedBigInteger('medicine_id')->index('v3_prescriptions_details_medicine_id_foreign');
            $table->unsignedBigInteger('route_administration_id')->nullable()->index('v3_prescriptions_details_route_administration_id_foreign');
            $table->unsignedBigInteger('prescription_id')->index('v3_prescriptions_details_prescription_id_foreign');
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
        Schema::dropIfExists('v3_prescriptions_details');
    }
};
