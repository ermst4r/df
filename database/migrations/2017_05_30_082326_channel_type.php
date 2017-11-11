<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChannelType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_type', function (Blueprint $table) {
            $table->integer('id')->primary()->unsigned();
            $table->string('channel_type');
            $table->integer('fk_channel_id')->unsigned();
            $table->foreign('fk_channel_id')->references('id')->on('channel')->onDelete('cascade')->onUpdate('restrict');
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
        Schema::dropIfExists('channel_type');
    }
}
