<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompositeKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composite_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field')->index();
            $table->integer('fk_feed_id')->unsigned();
            $table->timestamps();

            // Foreign key
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
        Schema::dropIfExists('composite_mapping');
    }
}
