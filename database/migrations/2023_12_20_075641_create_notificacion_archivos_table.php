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
        Schema::create('notificacion_archivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('notificacion_id')->nullable()->index('notificacion_archivos_notificacion_id_foreign');
            $table->string('nombre', 191)->nullable();
            $table->string('mime', 191)->nullable();
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
        Schema::dropIfExists('notificacion_archivos');
    }
};
