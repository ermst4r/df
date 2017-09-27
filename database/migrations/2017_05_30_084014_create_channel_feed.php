<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelFeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_feed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('fk_channel_id')->unsigned();
            $table->integer('fk_feed_id')->unsigned();
            $table->integer('fk_channel_type_id')->unsigned();
            $table->integer('fk_country_id')->unsigned();
            $table->tinyInteger('active')->default(\App\DfCore\DfBs\Enum\Channel::CHANNEL_ACTIVE);
            $table->boolean('updating')->default(false);
            $table->integer('update_interval')->default(\App\DfCore\DfBs\Enum\UpdateIntervals::DAILY);
            $table->dateTime('next_update')->nullable();
            $table->timestamps();

            $table->foreign('fk_channel_id')->references('id')->on('channel')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_country_id')->references('id')->on('channel_country')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_type_id')->references('id')->on('channel_type')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_feed');
    }
}
