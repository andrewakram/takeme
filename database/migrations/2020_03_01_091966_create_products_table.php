<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('menu_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('price_before')->nullable();
            $table->string('price_after')->nullable();
            $table->string('percent')->nullable();
            $table->string('quantity')->nullable();
            $table->string('selling')->default(0);
            $table->string('status')->default(1);
            $table->string('has_discount')->default(0);
            $table->string('rate')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
