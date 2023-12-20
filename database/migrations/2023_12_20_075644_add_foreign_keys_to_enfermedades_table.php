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
        Schema::table('enfermedades', function (Blueprint $table) {
            $table->foreign(['especialidad_id'])->references(['id'])->on('specialities')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enfermedades', function (Blueprint $table) {
            $table->dropForeign('enfermedades_especialidad_id_foreign');
        });
    }
};
