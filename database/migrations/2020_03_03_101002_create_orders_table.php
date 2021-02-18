<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('delegate_id')->unsigned()->nullable();
            $table->bigInteger('shop_id')->unsigned()->nullable();

            $table->string('confirm_code')->nullable();
            $table->string('title')->nullable();
            $table->string('notes')->nullable();

            $table->string('in_lat')->nullable();
            $table->string('in_lng')->nullable();
            $table->string('in_address')->nullable();
            $table->string('in_city_name')->nullable();
            $table->string('out_lat')->nullable();
            $table->string('out_lng')->nullable();
            $table->string('out_address')->nullable();
            $table->string('out_city_name')->nullable();

            $table->string('total_cost')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('copon_id')->nullable();
            $table->double('shop_rate')->nullable();
            $table->string('shop_comment')->nullable();
            $table->double('delegate_rate')->nullable();
            $table->string('delegate_comment')->nullable();
            $table->double('user_rate')->nullable();
            $table->string('user_comment')->nullable();

            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('delegate_id')->references('id')->on('delegates')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('copon_id')->references('id')->on('promo_codes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
