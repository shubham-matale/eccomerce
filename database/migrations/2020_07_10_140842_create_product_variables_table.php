<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('variable_original_price',15,2);
            $table->string('product_variable_options_name');
            $table->decimal('product_variable_option_size',15,2);
            $table->float('variable_selling_price',15,2);
            $table->foreign('product_id')->references('id')->on('products');
            $table->decimal('quantity',15,2)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variables');
    }
}
