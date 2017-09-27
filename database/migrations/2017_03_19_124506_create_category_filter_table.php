<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryFilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_filter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_feed_id')->unsigned();
            $table->integer('fk_category_id')->unsigned();
            $table->string('field')->index();
            $table->tinyInteger('condition');
            $table->boolean('visible')->default(true);
            $table->string('phrase');
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
        Schema::dropIfExists('category_filter');
        Schema::dropIfExists('category');
    }
}
