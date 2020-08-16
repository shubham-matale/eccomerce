<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softDeletes();
            $table->bigInteger('customer_id')->unsigned();
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('pincode');
            $table->string('latitude' );
            $table->string('longitude');
            $table->foreign('customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('addresses');
    }
}
