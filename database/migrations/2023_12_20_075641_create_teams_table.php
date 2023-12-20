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
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('doctor_id');
            $table->integer('ubicacion_id')->nullable();
            $table->text('slots');
            $table->text('available_days')->nullable();
            $table->string('message', 191);
            $table->enum('status', ['pending', 'approved', 'cancelled'])->default('pending');
            $table->boolean('regular')->default(false);
            $table->boolean('videollamada')->default(false);
            $table->boolean('domicilio')->default(false);
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
        Schema::dropIfExists('teams');
    }
};
