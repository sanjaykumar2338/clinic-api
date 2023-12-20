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
        Schema::create('nota_psicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paciente_id')->nullable()->index('nota_psicos_paciente_id_foreign');
            $table->unsignedBigInteger('doctor_id')->nullable()->index('nota_psicos_doctor_id_foreign');
            $table->string('nombre', 191)->nullable();
            $table->string('descripcion', 191)->nullable();
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
        Schema::dropIfExists('nota_psicos');
    }
};
