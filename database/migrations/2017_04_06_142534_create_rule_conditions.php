<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_condition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_rule_id')->unsigned();
            $table->mediumText('rule_options');
            $table->timestamps();

            // Foreign key
            $table->foreign('fk_rule_id')->references('id')->on('rules')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules_condition');
    }
}
