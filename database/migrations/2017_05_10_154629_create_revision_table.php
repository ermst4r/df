<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revision_channel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_feed_id')->unsigned()->index();
            $table->integer('fk_channel_feed_id')->unsigned()->index();
            $table->integer('fk_channel_type_id')->unsigned()->index();
            $table->string('generated_id')->index();
            $table->tinyInteger('revision_type')->default(\App\DfCore\DfBs\Enum\RevisionType::UPDATE);
            $table->string('revision_field_name')->index()->nullable();
            $table->text('revision_new_content')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('revision');
    }
}
