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
        Schema::create('v3_specialities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->index('v3_specialities_parent_id_foreign');
            $table->string('name', 1000)->nullable();
            $table->string('slug', 1000)->nullable();
            $table->string('internal_id', 250)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
            $table->string('medikit_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_specialities');
    }
};
