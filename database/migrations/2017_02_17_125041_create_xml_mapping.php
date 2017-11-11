<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXmlMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xml_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->string('xml_map_name');
            $table->string('mapped_xml_name');
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->timestamps();

            // Foreign key
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
        Schema::dropIfExists('xml_mapping');
    }
}
