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
        Schema::create('v3_speciality_diseases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('v3_speciality_id')->nullable()->index('v3_speciality_diseases_v3_speciality_id_foreign');
            $table->string('name');
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
        Schema::dropIfExists('v3_speciality_diseases');
    }
};
