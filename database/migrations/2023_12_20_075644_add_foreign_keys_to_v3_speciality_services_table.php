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
        Schema::table('v3_speciality_services', function (Blueprint $table) {
            $table->foreign(['v3_speciality_id'])->references(['id'])->on('v3_specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_speciality_services', function (Blueprint $table) {
            $table->dropForeign('v3_speciality_services_v3_speciality_id_foreign');
        });
    }
};
