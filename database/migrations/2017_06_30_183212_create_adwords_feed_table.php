<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adwords_feed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('adwords_account_id')->nullable();
            $table->boolean('updating')->default(false);
            $table->boolean('active')->default(true);
            $table->mediumText('adwords_api_message')->nullable();
            $table->integer('fk_feed_id')->unsigned();
            $table->integer('update_interval')->default(\App\DfCore\DfBs\Enum\UpdateIntervals::DAILY);
            $table->dateTime('next_update')->nullable();
            $table->timestamps();
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('adwords_feed', function (Blueprint $table) {
            Schema::dropIfExists('adwords_feed');
        });
    }
}
