<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdwordsRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adwords_revision', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_adwords_feed_id')->unsigned();
            $table->integer('fk_ads_preview_id')->unsigned();
            $table->string('generated_id')->index();
            $table->tinyInteger('revision_type')->index();
            $table->string('revision_field_name')->index()->nullable();
            $table->string('revision_new_content')->index()->nullable();

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
        Schema::dropIfExists('adwords_revision');
    }
}
