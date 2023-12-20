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
        Schema::table('v3_prescriptions_details', function (Blueprint $table) {
            $table->foreign(['medicine_id'])->references(['id'])->on('v3_medicines');
            $table->foreign(['route_administration_id'])->references(['id'])->on('v3_administration_routes');
            $table->foreign(['prescription_id'])->references(['id'])->on('v3_prescriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_prescriptions_details', function (Blueprint $table) {
            $table->dropForeign('v3_prescriptions_details_medicine_id_foreign');
            $table->dropForeign('v3_prescriptions_details_route_administration_id_foreign');
            $table->dropForeign('v3_prescriptions_details_prescription_id_foreign');
        });
    }
};
