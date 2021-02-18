<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartProductVariationOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_product_variation_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cart_product_variation_id')->unsigned()->nullable();
            $table->bigInteger('option_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('cart_product_variation_id')->references('id')->on('cart_product_variations')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product_variation_options');
    }
}
