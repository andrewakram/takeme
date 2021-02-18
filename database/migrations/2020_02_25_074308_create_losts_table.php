<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('losts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('en_lost');
            $table->text('ar_lost');
            $table->bigInteger('issue_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('issue_id')
                ->references('id')
                ->on('issues')
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
        Schema::dropIfExists('losts');
    }
}
