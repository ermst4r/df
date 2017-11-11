<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adwords_target', function (Blueprint $table) {
            $table->increments('id');
            $table->string('campaign_type')->nullable();
            $table->string('ad_delivery')->nullable();
            $table->mediumText('target_countries')->nullable();
            $table->mediumText('target_languages')->nullable();
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->timestamps();
            $table->foreign('fk_adwords_feed_id')->references('id')->on('adwords_feed')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adwords_target');
    }
}
