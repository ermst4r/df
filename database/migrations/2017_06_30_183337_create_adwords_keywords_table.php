<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adwords_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword')->index();
            $table->mediumText('keyword_type')->nullable();
            $table->tinyInteger('keyword_option')->nullable();
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->boolean('visible')->default(true);
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
        Schema::dropIfExists('adwords_keywords');
    }
}
