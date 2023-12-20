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
        Schema::create('ubicacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('estado_id');
            $table->string('nombre', 191);
            $table->longText('direccion');
            $table->string('telefono_consultorio', 191)->nullable();
            $table->string('telefono_consultorio2', 191)->nullable();
            $table->string('lat', 191)->nullable();
            $table->string('long', 191)->nullable();
            $table->string('color', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubicacions');
    }
};
