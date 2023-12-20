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
        Schema::create('mcl_campaign', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('clinic_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('name')->nullable();
            $table->date('date')->nullable();
            $table->time('schedule')->nullable();
            $table->text('message')->nullable();
            $table->string('header_image')->nullable();
            $table->string('main_image')->nullable();
            $table->string('final_image')->nullable();
            $table->string('header_image_url')->nullable();
            $table->string('main_image_url')->nullable();
            $table->string('final_image_url')->nullable();
            $table->binary('filters')->nullable();
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
        Schema::dropIfExists('mcl_campaign');
    }
};
