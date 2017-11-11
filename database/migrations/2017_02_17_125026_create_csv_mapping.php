<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('csvindex');
            $table->string('mapped_field_name')->index();
            $table->string('mapped_csv_name')->index();
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->timestamps();

            // Foreign keys
            $table->foreign('fk_feed_id')->references('id')->on('feeds')->onDelete('cascade')->onUpdate('restrict');;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_mapping');
    }
}
