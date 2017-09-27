<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDfLoggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('df_logger', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel')->index();
            $table->string('level')->index();
            $table->mediumText('message');
            $table->dateTime('time')->index();
            $table->timestamps();
            $table->index(['created_at', 'updated_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('df_logger');
    }
}
