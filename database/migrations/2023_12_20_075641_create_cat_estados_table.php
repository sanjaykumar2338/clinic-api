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
        Schema::create('cat_estados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clave', 191);
            $table->string('nombre', 191);
            $table->string('abrev', 191);
            $table->boolean('activo')->comment('Si es 1 quiere decir que el registro esta activo, y si es cero el registro esta desactivado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_estados');
    }
};
