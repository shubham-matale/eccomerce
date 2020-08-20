<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomProductVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_product_variables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('product_variable_id')->unsigned();
            $table->foreign('product_variable_id')->references('id')->on('product_variables');
            $table->bigInteger('ingradient_id')->unsigned();
            $table->foreign('ingradient_id')->references('id')->on('masala_ingradients');
            $table->decimal('default_value',8,2);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_product_variables');
    }
}
