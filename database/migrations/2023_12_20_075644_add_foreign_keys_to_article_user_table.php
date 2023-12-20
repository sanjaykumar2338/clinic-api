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
        Schema::table('article_user', function (Blueprint $table) {
            $table->foreign(['article_id'])->references(['id'])->on('articles')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_user', function (Blueprint $table) {
            $table->dropForeign('article_user_article_id_foreign');
            $table->dropForeign('article_user_user_id_foreign');
        });
    }
};
