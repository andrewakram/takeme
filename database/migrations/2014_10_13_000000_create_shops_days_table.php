<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shop_id')->unsigned()->nullable();
            $table->bigInteger('day_id')->unsigned()->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->softDeletes();
            $table->timestamps();

            /*$table->foreign('country_id')
                ->references('id')
                ->on('countries');*/
            $table->foreign('day_id')
                ->references('id')
                ->on('days')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops_days');
    }
}
