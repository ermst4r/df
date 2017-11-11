<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelFeedMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_feed_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_channel_id')->unsigned()->index();
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->integer('fk_channel_mapping_id')->unsigned()->index();
            $table->integer('fk_channel_feed_id')->unsigned()->index();
            $table->integer('fk_channel_type_id')->unsigned()->index();
            $table->string('feed_row_name')->index()->nullable();

            $table->foreign('fk_channel_id')->references('id')->on('channel')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_mapping_id')->references('id')->on('channel_mapping')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_feed_id')->references('id')->on('channel_feed')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_type_id')->references('id')->on('channel_type')->onDelete('cascade')->onUpdate('restrict');


            $table->timestamps();
        });


        Schema::table('spreadsheet_header_channel',function (Blueprint $table) {
            $table->foreign('fk_channel_type_id')->references('id')->on('channel_type')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_feed_id')->references('id')->on('channel_feed')->onDelete('cascade')->onUpdate('restrict');
        });

        Schema::table('revision_channel',function (Blueprint $table) {
            $table->foreign('fk_channel_feed_id')->references('id')->on('channel_feed')->onDelete('cascade')->onUpdate('restrict');
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
        Schema::dropIfExists('channel_feed_mapping');
    }
}
