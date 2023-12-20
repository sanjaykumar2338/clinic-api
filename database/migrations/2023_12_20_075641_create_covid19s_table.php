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
        Schema::create('covid19s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->double('positivosConfirmados')->nullable();
            $table->double('defuncionesEstimadas')->nullable();
            $table->double('activosEstimados')->nullable();
            $table->double('confirmados')->nullable();
            $table->double('negativos')->nullable();
            $table->double('sospechosos')->nullable();
            $table->double('defunciones')->nullable();
            $table->double('recuperados')->nullable();
            $table->double('activos')->nullable();
            $table->double('porcentajeMujeres')->nullable();
            $table->double('porcentajeHombres')->nullable();
            $table->double('hospitalizados')->nullable();
            $table->double('ambulatorios')->nullable();
            $table->double('hipertension')->nullable();
            $table->double('obesidad')->nullable();
            $table->double('diabetes')->nullable();
            $table->double('tabaquismo')->nullable();
            $table->text('fuente')->nullable();
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
        Schema::dropIfExists('covid19s');
    }
};
