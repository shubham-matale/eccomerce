<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sub_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('product_subcategory_name');
            $table->text('product_subcategory_image_url');
            $table->bigInteger('product_category_id')->unsigned();
            $table->boolean('is_active');



        });

        Schema::table('product_sub_categories', function(Blueprint $table) {
            $table->foreign('product_category_id')->references('id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_subcategory', function(Blueprint $table) {
            $table->dropForeign('product_subcategory_product_category_id_foreign');
        });
        Schema::dropIfExists('product_sub_categories');
    }
}
