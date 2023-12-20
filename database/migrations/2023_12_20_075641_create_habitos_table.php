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
        Schema::create('habitos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('detalle_id')->nullable();
            $table->string('comidas', 191);
            $table->integer('alergia_alimentos');
            $table->string('alimentos_no_agrado', 191);
            $table->string('hora_hambre_mayor', 191);
            $table->integer('suplemento');
            $table->string('vasos_agua', 191);
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
        Schema::dropIfExists('habitos');
    }
};
