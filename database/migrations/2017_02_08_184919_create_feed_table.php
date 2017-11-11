<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feed_name')->index();
            $table->string('feed_custom_parser')->index()->nullable();
            $table->mediumText('feed_url');
            $table->string('feed_type')->index();
            $table->boolean('active')->default(true);
            $table->dateTime('feed_updated')->nullable();
            $table->string('xml_root_node')->nullable();
            $table->string('prepend_nodes')->nullable();
            $table->string('prepend_identifier')->nullable();
            $table->tinyInteger('feed_status')->default(\App\DfCore\DfBs\Enum\ImportStatus::PENDING);
            $table->integer('fk_store_id')->unsigned();
            $table->integer('update_interval')->default(\App\DfCore\DfBs\Enum\UpdateIntervals::DAILY);
            $table->integer('fetched_records')->default(0)->index();
            $table->dateTime('next_update')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('fk_store_id')->references('id')->on('stores')->onDelete('cascade')->onUpdate('restrict');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feeds');
    }
}
