<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductVariationOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_variation_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_product_var_id')->unsigned()->nullable();
            $table->bigInteger('option_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('order_product_var_id')->references('id')->on('order_product_variations')->onDelete('cascade');
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
        Schema::dropIfExists('order_product_variation_options');
    }
}
