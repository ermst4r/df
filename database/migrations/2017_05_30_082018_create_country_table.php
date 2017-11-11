<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_country', function (Blueprint $table) {
            $table->integer('id')->primary()->unsigned();
            $table->string('country');
            $table->timestamps();

        });


        Schema::table('channel',function (Blueprint $table) {
            $table->foreign('fk_country_id')->references('id')->on('channel_country')->onDelete('cascade')->onUpdate('restrict');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country');
    }
}
