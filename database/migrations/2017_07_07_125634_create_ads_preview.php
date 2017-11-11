<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsPreview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_preview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('headline_1')->index();
            $table->string('headline_2')->index();
            $table->string('description')->index();
            $table->string('path_1')->index();
            $table->string('path_2')->index();
            $table->mediumText('final_url');
            $table->string('errors')->nullable()->index();
            $table->mediumText('adwords_api_message')->nullable();
            $table->string('update_hash')->nullable();
            $table->boolean('is_valid')->default(true);

            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->integer('fk_adgroup_preview_id')->unsigned();
            $table->integer('fk_campaigns_preview_id')->unsigned();
            $table->integer('fk_adwords_ad_id')->unsigned();
            $table->string('generated_id')->index();
            $table->bigInteger('adwords_id')->index()->unsigned();
            $table->boolean('delete_from_adwords')->default(false);


            $table->foreign('fk_adwords_feed_id')->references('id')->on('adwords_feed')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_adgroup_preview_id')->references('id')->on('adgroup_preview')->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('fk_campaigns_preview_id')->references('id')->on('ad_campaign_preview')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_adwords_ad_id')->references('id')->on('adwords_ads')->onDelete('cascade')->onUpdate('restrict');

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
        Schema::dropIfExists('ads_preview');
    }
}
