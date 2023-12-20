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
        Schema::create('mcl_appointment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('clinic_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->integer('doctor')->nullable();
            $table->date('date')->nullable();
            $table->integer('room')->nullable();
            $table->string('duration', 100)->nullable();
            $table->integer('patient')->nullable();
            $table->integer('service')->nullable();
            $table->text('description')->nullable();
            $table->binary('slot')->nullable();
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
        Schema::dropIfExists('mcl_appointment');
    }
};
