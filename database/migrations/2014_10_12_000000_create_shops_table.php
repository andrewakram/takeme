<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id');
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('jwt')->nullable();
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('description')->nullable();
            $table->string('rate')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->tinyInteger('verified')->nullable();
            $table->tinyInteger('suspend')->default(0);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('address')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            /*$table->foreign('country_id')
                ->references('id')
                ->on('countries');*/
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('shops');
    }
}
