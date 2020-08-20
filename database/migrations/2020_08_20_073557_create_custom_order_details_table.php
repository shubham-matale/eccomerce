<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('order_details_id')->unsigned();
            $table->foreign('order_details_id')->references('id')->on('order_details');
            $table->bigInteger('ingradient_id')->unsigned();
            $table->foreign('ingradient_id')->references('id')->on('masala_ingradients');
            $table->decimal('required_qty',8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_order_details');
    }
}
