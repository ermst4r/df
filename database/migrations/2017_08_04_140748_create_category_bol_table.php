<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryBolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_bol', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_bol_id',false,true)->index();
            $table->foreign('fk_bol_id')->references('id')->on('bol_feed')->onDelete('cascade');

            $table->integer('fk_category_filter_id',false,true)->index();
            $table->foreign('fk_category_filter_id')->references('id')->on('category_filter')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_bol');
    }
}
