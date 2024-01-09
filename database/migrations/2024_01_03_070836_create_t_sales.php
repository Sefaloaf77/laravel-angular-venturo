<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('m_customer_id')->comment('Fill with id of m_customer');
            $table->bigInteger('m_voucher_id')
                ->comment('Fill with id of m_voucher');
            $table->double('voucher_nominal')
                ->comment('Fill with nominal of voucher')->default(0);
            $table->bigInteger('m_discount_id')->comment('Fill with id of m_discount');
            $table->dateTime('date');

            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);

            $table->index('m_customer_id');
            $table->index('m_voucher_id');
            $table->index('m_discount_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_sales');
    }
}
