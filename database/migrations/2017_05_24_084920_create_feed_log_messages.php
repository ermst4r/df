<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedLogMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_logger', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('log_message');
            $table->string('log_type')->default(\App\DfCore\DfBs\Enum\LogStates::DEBUG);
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->timestamps();
            $table->index(['created_at', 'updated_at']);
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_logger');
    }
}
