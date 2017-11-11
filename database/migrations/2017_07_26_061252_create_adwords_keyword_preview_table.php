<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsKeywordPreviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_keyword_preview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('formatted_keyword')->index();
            $table->boolean('delete_keyword')->default(false);
            $table->integer('keyword_type')->default(\App\DfCore\DfBs\Enum\AdwordsOptions::NORMAL_KEYWORD);
            $table->bigInteger('adwords_id')->default(0)->index()->unsigned();
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->integer('fk_adwords_keyword_id')->unsigned();
            $table->integer('fk_adgroup_preview_id')->unsigned();
            $table->timestamps();
            $table->foreign('fk_adwords_feed_id')->references('id')->on('adwords_feed')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_adgroup_preview_id')->references('id')->on('adgroup_preview')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_adwords_keyword_id')->references('id')->on('adwords_keywords')->onDelete('cascade')->onUpdate('restrict');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_keyword_preview');
    }
}
