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
        Schema::create('egreso_receta_medica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receta_medica_id')->nullable()->index('egreso_receta_medica_receta_medica_id_foreign');
            $table->unsignedBigInteger('egreso_id')->nullable()->index('egreso_receta_medica_egreso_id_foreign');
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
        Schema::dropIfExists('egreso_receta_medica');
    }
};
