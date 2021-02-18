<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelegatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('jwt')->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->string('user_code')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->tinyInteger('verified')->nullable();
            $table->tinyInteger('suspend')->default(0);
            $table->tinyInteger('online')->default(1);
            $table->bigInteger('country_id')->unsigned()->default(1);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('token')->nullable();
            $table->bigInteger('wallet')->default(0);
            $table->bigInteger('points')->default(0);
            $table->tinyInteger('wallet_flag')
                ->comment('0 => off , 1 => on')->default(0);
            $table->bigInteger('promo_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_num')->nullable();
            $table->string('car_num')->nullable();
            $table->string('car_text')->nullable();
            $table->bigInteger('car_level')->unsigned()->nullable();
            $table->string('national_id')->nullable();
            $table->string('national_id_type')->nullable();
            $table->string('image')->nullable();
            $table->string('front_car_image')->nullable();
            $table->string('back_car_image')->nullable();
            $table->string('insurance_image')->nullable();
            $table->string('license_image')->nullable();
            $table->string('civil_image')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            /*$table->foreign('country_id')
                ->references('id')
                ->on('countries');*/
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('car_level')
                ->references('id')
                ->on('car_levels')
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
        Schema::dropIfExists('delegates');
    }
}
