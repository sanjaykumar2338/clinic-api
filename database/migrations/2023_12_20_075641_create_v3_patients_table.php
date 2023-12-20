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
        Schema::create('v3_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->tinyInteger('gender')->nullable()->default(3)->comment('1=Hombre, 2=Mujer, 3=Prefiero no especificar');
            $table->integer('age')->nullable();
            $table->tinyInteger('civil_status')->nullable();
            $table->string('occupation')->nullable();
            $table->text('education')->nullable();
            $table->text('caregiver')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('vital_sign_assement')->nullable();
            $table->string('address')->nullable();
            $table->string('place_origin')->nullable();
            $table->string('location')->nullable();
            $table->integer('doctor')->nullable();
            $table->string('phone')->nullable();
            $table->string('RFC')->nullable();
            $table->string('tax_regime', 3)->nullable();
            $table->string('cfdi_use', 3)->nullable();
            $table->string('blood_type')->nullable();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->boolean('sexual_activity')->nullable();
            $table->integer('exercise')->nullable();
            $table->integer('alcoholism')->nullable();
            $table->integer('smoking')->nullable();
            $table->string('ta')->nullable();
            $table->string('temp')->nullable();
            $table->string('fc')->nullable();
            $table->longText('recent_medications')->nullable();
            $table->boolean('app_transfusions')->nullable();
            $table->boolean('app_diabetes')->nullable();
            $table->boolean('app_cancer')->nullable();
            $table->boolean('app_hypertension')->nullable();
            $table->boolean('app_asthma')->nullable();
            $table->boolean('app_rhinitis')->nullable();
            $table->text('app_allergies')->nullable();
            $table->string('has_allergy')->nullable();
            $table->string('mk_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable()->index('patients_user_id_foreign');
            $table->unsignedBigInteger('patient_id')->nullable()->index('patients_patient_id_foreign');
            $table->string('curp', 20)->nullable();
            $table->string('religion')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('diet')->nullable();
            $table->string('formula')->nullable();
            $table->text('medicines')->nullable();
            $table->text('nursing_comment')->nullable();
            $table->text('others_kardex')->nullable();
            $table->string('expediente_id')->nullable();
            $table->text('other_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v3_patients');
    }
};
