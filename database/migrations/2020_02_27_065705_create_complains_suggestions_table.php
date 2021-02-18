<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainsSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains_suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->longText('description');
            $table->bigInteger('issue_id')->unsigned()->nullable();
            $table->bigInteger('lost_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('issue_id')
                ->references('id')
                ->on('issues')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('lost_id')
                ->references('id')
                ->on('losts')
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
        Schema::dropIfExists('complains_suggestions');
    }
}
