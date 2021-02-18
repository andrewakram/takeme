<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('department_id')->unsigned()->nullable();;
            $table->string('code');
            $table->bigInteger('value');
            $table->enum('type',[0,1])->comment('0=>Fixed value , 1=>percent value');/*0 = fixed discount , 1 = percentage discount*/
            $table->string('country_ids');/*valid countries*/
            $table->string('car_level_ids');/*valid car levels*/
            $table->bigInteger('expire_times'); /*num of using this code*/
            $table->timestamp('expire_at');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
}
