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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('waiting_time', 191)->nullable();
            $table->text('rating')->nullable();
            $table->string('avg_rating', 191)->nullable();
            $table->text('comment');
            $table->integer('patient_id');
            $table->string('keep_anonymous', 191)->default('off');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index('feedbacks_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
};
