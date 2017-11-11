<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_mapping', function (Blueprint $table) {
            $table->integer('id')->primary()->unsigned();
            $table->string('channel_field_name');
            $table->string('description');
            $table->tinyInteger('channel_field_type')->default(\App\DfCore\DfBs\Enum\Channel::FIELD_MANDATORY);
            $table->integer('fk_channel_id')->unsigned();
            $table->integer('fk_channel_type_id')->unsigned();

            $table->foreign('fk_channel_id')->references('id')->on('channel')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('fk_channel_type_id')->references('id')->on('channel_type')->onDelete('cascade')->onUpdate('restrict');

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
        Schema::dropIfExists('channel_mapping');
    }
}
