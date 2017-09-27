<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RulesChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_channel', function (Blueprint $table) {

            $table->integer('fk_channel_feed_id',false,true)->index();
            $table->foreign('fk_channel_feed_id')->references('id')->on('channel_feed')->onDelete('cascade');

            $table->integer('fk_rule_id',false,true)->index();
            $table->foreign('fk_rule_id')->references('id')->on('rules')->onDelete('cascade');

            $table->integer('fk_channel_type_id')->unsigned()->index();
            $table->foreign('fk_channel_type_id')->references('id')->on('channel_type')->onDelete('cascade');

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
        Schema::dropIfExists('rules_channel');
    }
}
