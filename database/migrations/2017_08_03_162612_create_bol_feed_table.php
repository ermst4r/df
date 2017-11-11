<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBolFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bol_feed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->mediumText('public_key');
            $table->mediumText('private_key');
            $table->tinyInteger('status')->default(0);
            $table->integer('fk_feed_id')->unsigned();
            $table->integer('update_interval')->default(\App\DfCore\DfBs\Enum\UpdateIntervals::DAILY);
            $table->dateTime('next_update')->nullable();

            $table->timestamps();
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bol_feed');
    }
}
