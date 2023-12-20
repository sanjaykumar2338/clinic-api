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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 191);
            $table->integer('author_id');
            $table->text('tags')->nullable();
            $table->integer('views')->nullable();
            $table->integer('likes')->nullable();
            $table->string('title', 191);
            $table->text('description');
            $table->string('image', 191)->nullable();
            $table->string('excerpt', 191);
            $table->boolean('is_featured')->default(false);
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
        Schema::dropIfExists('articles');
    }
};
