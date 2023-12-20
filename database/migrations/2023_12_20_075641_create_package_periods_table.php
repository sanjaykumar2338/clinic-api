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
        Schema::create('package_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('singular')->nullable();
            $table->string('plural')->nullable();
            $table->string('adj_singular')->nullable();
            $table->string('adj_plural')->nullable();
            $table->string('noun_singular')->nullable();
            $table->string('noun_plural')->nullable();
            $table->integer('period')->default(0);
            $table->string('type')->default('days');
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
        Schema::dropIfExists('package_periods');
    }
};
