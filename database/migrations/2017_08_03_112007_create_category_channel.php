<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_channel', function (Blueprint $table) {

            $table->integer('fk_category_filter_id')->unsigned()->index();
            $table->integer('fk_channel_feed_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('fk_channel_feed_id')->references('id')->on('channel_feed')->onDelete('cascade');
            $table->foreign('fk_category_filter_id')->references('id')->on('category_filter')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_channel');
    }
}
