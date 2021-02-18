<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryCarLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_car_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('car_level_id')->unsigned();
            $table->tinyInteger('schedule_flag')->default(1);
            $table->double('start_trip_unit', 8, 2);
            $table->double('waiting_trip_unit', 8, 2);
            $table->double('distance_trip_unit', 8, 2);
            $table->double('rush_start_trip_unit', 8, 2);
            $table->double('rush_waiting_trip_unit', 8, 2);
            $table->double('rush_distance_trip_unit', 8, 2);
            $table->double('cancellation_trip_unit', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('car_level_id')
                ->references('id')
                ->on('car_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
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
        Schema::dropIfExists('country_car_levels');
    }
}
