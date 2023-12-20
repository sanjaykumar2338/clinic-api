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
        Schema::table('v3_specialities', function (Blueprint $table) {
            $table->foreign(['parent_id'])->references(['id'])->on('v3_specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_specialities', function (Blueprint $table) {
            $table->dropForeign('v3_specialities_parent_id_foreign');
        });
    }
};
