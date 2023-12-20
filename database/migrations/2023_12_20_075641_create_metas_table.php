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
        Schema::create('metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('meta_key', 191);
            $table->text('meta_value')->nullable();
            $table->string('metable_type', 191);
            $table->unsignedBigInteger('metable_id');
            $table->timestamps();

            $table->index(['metable_type', 'metable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
    }
};
