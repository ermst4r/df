<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCampaignPreview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_campaign_preview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('campaign_name')->index();
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->tinyInteger('existing_campaign')->default(1)->index();
            $table->bigInteger('adwords_id')->index()->unsigned();
            $table->boolean('delete_from_adwords')->default(false);
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
        Schema::dropIfExists('ad_campaign_preview');
    }
}
