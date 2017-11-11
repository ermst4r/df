<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RuleBol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_bol', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_bol_id',false,true)->index();
            $table->foreign('fk_bol_id')->references('id')->on('bol_feed')->onDelete('cascade');

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
        Schema::dropIfExists('rules_bol');
    }
}
