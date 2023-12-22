<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->comment('Fill with name of customer');
            $table->string('email',50)->comment('Fill with email of customer');
            $table->string('phone_number',25)->comment('Fill with phone number of customer');
            $table->date('date_of_birth')->comment('Fill with date of birth of customer');
            $table->string('photo',100)->comment('Fill with customer profile picture');
            $table->tinyInteger('is_verified')->comment('Fill with "1" if customer already verified. Fill with "0" if customer not verified');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
