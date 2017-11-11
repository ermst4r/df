<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RulesAdwordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_adwords', function (Blueprint $table) {

            $table->integer('fk_adwords_feed_id',false,true)->index();
            $table->foreign('fk_adwords_feed_id')->references('id')->on('adwords_feed')->onDelete('cascade');

            $table->integer('fk_rule_id',false,true)->index();
            $table->foreign('fk_rule_id')->references('id')->on('rules')->onDelete('cascade');
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
        //
    }
}
