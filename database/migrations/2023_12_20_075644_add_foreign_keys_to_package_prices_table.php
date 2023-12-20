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
        Schema::table('package_prices', function (Blueprint $table) {
            $table->foreign(['package_id'])->references(['id'])->on('packages');
            $table->foreign(['package_period_id'])->references(['id'])->on('package_periods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_prices', function (Blueprint $table) {
            $table->dropForeign('package_prices_package_id_foreign');
            $table->dropForeign('package_prices_package_period_id_foreign');
        });
    }
};
