<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsConfigurationTablee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adwords_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('campaign_name')->nullable();
            $table->string('adgroup_name')->nullable();

            $table->double('cpc',6,2)->nullable()->default(0);
            $table->double('daily_budget',6,2)->nullable()->default(0);
            $table->boolean('existing_campaign')->default(false);
            $table->tinyInteger('live_option')->default(\App\DfCore\DfBs\Enum\AdwordsOptions::PREVIEW_MODUS);
            $table->bigInteger('campaign_adwords_id')->default(0)->index();
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->boolean('live')->default(false);
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
        Schema::dropIfExists('adwords_configuration');
    }
}
