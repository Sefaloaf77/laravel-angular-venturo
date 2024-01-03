<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_discount', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('m_customer_id')->comment('Fill with id of m_customer');
            $table->bigInteger('m_promo_id')
                ->comment('Fill with id of m_promo');
            $table->boolean('tahsin');
            $table->boolean('competency_matrix');
            $table->boolean('late_under_3');
            $table->boolean('full_absensi');

            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);

            $table->index('m_customer_id');
            $table->index('m_promo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_discount');
    }
}
