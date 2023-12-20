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
        Schema::create('mcl_nurse', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('clinic_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('license_number')->nullable();
            $table->string('academic_degree')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->binary('permissions')->nullable();
            $table->string('signature')->nullable();
            $table->string('officialId_front')->nullable();
            $table->string('officialId_back')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcl_nurse');
    }
};
