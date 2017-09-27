<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adwords_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('headline_1');
            $table->string('headline_2');
            $table->string('description');
            $table->string('path_1');
            $table->string('path_2');
            $table->string('final_url');
            $table->boolean('is_backup_template')->default(false);
            $table->integer('parent_id')->index()->default(0);
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
        Schema::dropIfExists('adwords_ads');
    }
}
