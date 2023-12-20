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
        Schema::table('v3_addresses', function (Blueprint $table) {
            $table->foreign(['patient_id'])->references(['id'])->on('v3_patients')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_addresses', function (Blueprint $table) {
            $table->dropForeign('v3_addresses_patient_id_foreign');
        });
    }
};
