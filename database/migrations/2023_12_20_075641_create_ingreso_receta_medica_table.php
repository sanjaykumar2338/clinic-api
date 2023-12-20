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
        Schema::create('ingreso_receta_medica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receta_medica_id')->nullable()->index('ingreso_receta_medica_receta_medica_id_foreign');
            $table->unsignedBigInteger('ingreso_id')->nullable()->index('ingreso_receta_medica_ingreso_id_foreign');
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
        Schema::dropIfExists('ingreso_receta_medica');
    }
};
