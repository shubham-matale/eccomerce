<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSearchDisplayOrderToManyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('searchString')->default('');
            $table->integer('displayOrder')->default(99);
        });

        Schema::table('product_sub_categories', function (Blueprint $table) {
            $table->string('searchString')->default('');
            $table->integer('displayOrder')->default(99);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('searchString')->default('');
            $table->integer('displayOrder')->default(99);
        });

        Schema::table('product_variable_options', function (Blueprint $table) {
            $table->integer('displayOrder')->default(99);
        });

        Schema::table('product_variables', function (Blueprint $table) {
            $table->integer('displayOrder')->default(99);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            //
        });
    }
}
