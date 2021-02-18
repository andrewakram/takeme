<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_product_variations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cart_product_id')->unsigned()->nullable();
            $table->bigInteger('variation_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('cart_product_id')->references('id')->on('cart_products')->onDelete('cascade');
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product_variations');
    }
}
