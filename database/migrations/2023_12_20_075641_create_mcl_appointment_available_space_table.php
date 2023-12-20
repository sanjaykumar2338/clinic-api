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
        Schema::create('mcl_appointment_available_space', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('doctor')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->integer('room')->nullable();
            $table->string('duration', 100)->nullable();
            $table->binary('days')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->integer('admin')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcl_appointment_available_space');
    }
};
