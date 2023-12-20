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
        Schema::table('memberships', function (Blueprint $table) {
            $table->foreign(['package_id'])->references(['id'])->on('packages');
            $table->foreign(['user_id'])->references(['id'])->on('users');
            $table->foreign(['package_price_id'])->references(['id'])->on('package_prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropForeign('memberships_package_id_foreign');
            $table->dropForeign('memberships_user_id_foreign');
            $table->dropForeign('memberships_package_price_id_foreign');
        });
    }
};
