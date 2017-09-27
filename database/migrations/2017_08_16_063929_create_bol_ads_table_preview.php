<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBolAdsTablePreview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bol_ads_preview', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_bol_id')->unsigned();
            $table->integer('fk_feed_id')->unsigned();
            $table->integer('fk_bol_ad_id')->unsigned();
            $table->string('ean')->index();
            $table->double('price',6,2);
            $table->string('title');
            $table->string('fullfilment');
            $table->string('condition');
            $table->string('delivery_code');
            $table->string('stock');
            $table->string('reference_code');
            $table->mediumText('description');
            $table->boolean('failed')->default(false);
            $table->boolean('in_bol_com')->default(false);
            $table->string('api_response')->nullable();
            $table->timestamps();

            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_bol_id')->references('id')->on('bol_feed')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_bol_ad_id')->references('id')->on('bol_ads')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bol_ads_preview');
    }
}
