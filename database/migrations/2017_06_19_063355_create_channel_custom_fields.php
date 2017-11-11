<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelCustomFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_custom_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_channel_feed_id')->unsigned()->index();
            $table->integer('fk_channel_type_id')->unsigned()->index();
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->string('custom_field_name')->index();
            $table->string('field_name')->index();
            $table->foreign('fk_channel_type_id')->references('id')->on('channel_type')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_feed_id')->references('id')->on('channel_feed')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_custom_mapping');
    }
}
