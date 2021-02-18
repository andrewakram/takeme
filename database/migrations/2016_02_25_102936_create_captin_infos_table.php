<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaptinInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captin_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('accept')->default(0);
            $table->tinyInteger('busy')->default(0);
            $table->tinyInteger('online')->default(0);
            $table->string('driving_license')->nullable();
            $table->bigInteger('working_hours')->default(1);
            $table->string('id_image_1')->nullable();
            $table->string('id_image_2')->nullable();
            $table->string('car_license_1')->nullable();
            $table->string('car_license_2')->nullable();
            $table->string('feesh')->nullable();
            $table->string('car_color');
            $table->string('color_name');
            $table->string('car_num');
            $table->string('car_model');
            $table->bigInteger('car_level')->unsigned()->default(1);
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('captin_infos');
    }
}
