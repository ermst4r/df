<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBolAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bol_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_bol_id')->unsigned();
            $table->integer('fk_feed_id')->unsigned();
            $table->string('ean')->index();
            $table->string('price');
            $table->string('title');
            $table->string('fullfilment');
            $table->string('condition');
            $table->string('delivery_code');
            $table->string('reference_code');
            $table->string('stock');
            $table->mediumText('description');
            $table->timestamps();
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_bol_id')->references('id')->on('bol_feed')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bol_ads');
    }
}
