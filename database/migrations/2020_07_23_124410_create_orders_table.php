<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Invoice_No');
            $table->decimal('total_amount',15,2);
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('address_id')->unsigned();
            $table->decimal('sub_total',15,2);
            $table->decimal('tax',15,2);
            $table->decimal('discount',15,2)->default(0.00);
            $table->bigInteger('coupon_code_id')->unsigned()->nullable();
            $table->string('alternate_no');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('coupon_code_id')->references('id')->on('coupon_codes');
            $table->decimal('delivery_charge',15,2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
