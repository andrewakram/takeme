<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriedtCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criedt_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_num');
            $table->string('expire_date');
            $table->bigInteger('cvv');
            $table->string('name');
            $table->tinyInteger('active')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('criedt_cards');
    }
}
