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
        Schema::create('user_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->text('experiences')->nullable();
            $table->text('specializations')->nullable();
            $table->text('memberships')->nullable();
            $table->text('educations')->nullable();
            $table->text('awards')->nullable();
            $table->text('services')->nullable();
            $table->string('clabe', 191)->nullable();
            $table->unsignedBigInteger('banco_id')->nullable();
            $table->text('subespecialidades')->nullable();
            $table->string('avatar', 191)->nullable();
            $table->string('banner', 191)->nullable();
            $table->string('signature', 191)->nullable();
            $table->string('gender', 191)->nullable();
            $table->string('gender_title', 191)->nullable();
            $table->string('sub_heading', 191)->nullable();
            $table->string('tagline', 191)->nullable();
            $table->text('short_desc')->nullable();
            $table->text('description')->nullable();
            $table->string('delete_reason', 191)->nullable();
            $table->string('delete_description', 191)->nullable();
            $table->string('payout_id', 191)->nullable();
            $table->text('profile_searchable')->nullable();
            $table->text('weekly_alerts')->nullable();
            $table->text('disable_account')->nullable();
            $table->text('message_alerts')->nullable();
            $table->text('verify_medical')->nullable();
            $table->integer('consultation_fee')->nullable();
            $table->text('saved_hospitals')->nullable();
            $table->text('saved_doctors')->nullable();
            $table->text('saved_articles')->nullable();
            $table->text('downloads')->nullable();
            $table->boolean('verify_registration')->default(false);
            $table->integer('recommendation')->nullable();
            $table->integer('votes')->nullable();
            $table->text('available_days')->nullable();
            $table->text('working_time')->nullable();
            $table->text('liked_answers')->nullable();
            $table->integer('starting_price')->nullable();
            $table->text('payout_settings')->nullable();
            $table->text('instagram')->nullable();
            $table->text('linkedin')->nullable();
            $table->timestamps();
            $table->text('gallery')->nullable();
            $table->text('gallery_videos')->nullable();
            $table->string('sat_regimen_fiscal', 191)->nullable();
            $table->string('sat_cp', 191)->nullable();
            $table->string('sat_password', 191)->nullable();
            $table->string('sat_name_social', 191)->nullable();
            $table->string('sat_rfc', 191)->nullable();
            $table->string('sat_usoCFDI', 191)->nullable();
            $table->string('sat_cer_valid_to', 191)->nullable();
            $table->string('sat_cer_serie', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_metas');
    }
};
