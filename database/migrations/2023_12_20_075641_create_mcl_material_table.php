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
        Schema::create('mcl_material', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('clinic_id')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('description_center')->nullable();
            $table->string('batch')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('material_type')->nullable();
            $table->string('location')->nullable();
            $table->string('available_stock')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->date('entry_date_warehouse')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('cost')->nullable();
            $table->string('public_price')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->string('stock_type', 100)->nullable();
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
        Schema::dropIfExists('mcl_material');
    }
};
