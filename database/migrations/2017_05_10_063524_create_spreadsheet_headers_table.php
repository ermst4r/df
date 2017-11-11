<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpreadsheetHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spreadsheet_header_channel', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('spreadsheet_header');
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->integer('fk_channel_feed_id')->unsigned()->index();
            $table->integer('fk_channel_type_id')->unsigned()->index();
            $table->timestamps();

            // Foreign keys
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');







        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spreadsheet_header_channel');
    }
}
