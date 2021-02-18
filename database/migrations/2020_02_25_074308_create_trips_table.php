<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['urgent', 'scheduled']);

            $table->bigInteger('status')->unsigned()->nullable();
            /*1=waiting_captin , 2=trip_started , 3=trip_finished , 4=trip_cancelled*/

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('driver_id')->unsigned()->nullable();
            $table->bigInteger('car_level_id')->unsigned()->nullable();

            $table->enum('canceled_by', [0, 1])->nullable();//0 = user , 1 = driver
            $table->bigInteger('cancel_id')->unsigned()->nullable();
            $table->string('cancel_reason')->nullable();

            $table->bigInteger('promo_id')->unsigned()->nullable();

            $table->string('start_address')->nullable();
            $table->string('start_lat')->nullable();
            $table->string('start_lng')->nullable();
            $table->string('end_address')->nullable();
            $table->string('end_lat')->nullable();
            $table->string('end_lng')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('trip_time')->default(0);
            $table->bigInteger('waiting_time')->default(0);

            $table->date('date')->nullable();
            $table->time('time')->nullable();

            $table->tinyInteger('payment')->default(0);//0 = cash , 1 = online

            $table->float('user_rate', 10, 1)->default('0');
            $table->float('driver_rate', 10, 1)->default('0');
            $table->text('user_comment')->nullable();
            $table->text('driver_comment')->nullable();
            $table->double('trip_distance',10,5)->default('0');
            $table->double('trip_total',10,1)->default('0');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cancel_id')
                ->references('id')
                ->on('cancelling_reasons')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('promo_id')
                ->references('id')
                ->on('promo_codes')
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
        Schema::dropIfExists('trips');
    }
}
