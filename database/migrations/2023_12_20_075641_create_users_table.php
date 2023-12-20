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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('user_type', ['admin', 'superadmin', 'doctor', 'user', 'other', 'nurse'])->default('other');
            $table->bigInteger('dr_id')->nullable();
            $table->integer('nurse_id')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->integer('doctor_registro_id')->nullable()->comment('Doctor que registró al paciente, desde la sección de pacientes');
            $table->string('first_name', 191);
            $table->string('last_name', 191);
            $table->string('last_name2')->nullable();
            $table->string('slug', 191)->unique();
            $table->string('email', 191)->unique();
            $table->string('password', 191)->nullable();
            $table->integer('doctor_seleccionado')->nullable()->comment('Doctor que ha seleccionado el asistente, para poder hacer consultas en base a ese doctor');
            $table->integer('location_id')->nullable();
            $table->string('verification_code', 191)->nullable();
            $table->tinyInteger('user_verified')->nullable();
            $table->string('package_expiry', 191)->nullable();
            $table->string('referencia', 191)->nullable()->comment('Columna para capturar referencias');
            $table->text('tokenfarmacia')->nullable();
            $table->string('conekta_id', 191)->nullable();
            $table->string('stripe_account', 191)->nullable();
            $table->string('stripe_customer', 191)->nullable();
            $table->text('stripe_payment_method')->nullable();
            $table->integer('step')->default(1);
            $table->rememberToken();
            $table->string('social_id', 191)->nullable();
            $table->string('secure')->nullable();
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
        Schema::dropIfExists('users');
    }
};
