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
        Schema::create('preoperatoria_receta_medica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receta_medica_id')->nullable()->index('preoperatoria_receta_medica_receta_medica_id_foreign');
            $table->unsignedBigInteger('preoperatoria_id')->nullable()->index('preoperatoria_receta_medica_preoperatoria_id_foreign');
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
        Schema::dropIfExists('preoperatoria_receta_medica');
    }
};
