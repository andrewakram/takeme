<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_paths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trip_id')->unsigned();
            $table->tinyInteger('status')
                ->comment('0 => pickup , 1 => firstLocation , 2 => secondLocation');
            $table->string('address');
            $table->string('lat');
            $table->string('lng');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('trip_id')
                ->references('id')
                ->on('trips')
                ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_paths');
    }
}
