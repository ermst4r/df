<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdgroupPreview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adgroup_preview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adgroup_name')->index();
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->integer('fk_campaigns_preview_id')->unsigned();
            $table->bigInteger('adwords_id')->index()->unsigned();
            $table->boolean('delete_from_adwords')->default(false);
            $table->timestamps();
            $table->foreign('fk_adwords_feed_id')->references('id')->on('adwords_feed')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_campaigns_preview_id')->references('id')->on('ad_campaign_preview')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adgroup_preview');
    }
}
