<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('variation_id')->unsigned()->nullable();
            $table->string('price')->nullable();
            $table->string('status')->nullable()
                ->comment('0 => not available, 1 => available');
            $table->tinyInteger('type')->nullable()
                ->comment('0 => radio, 1 => checkbox');
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('options');
    }
}
