<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAgeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('happ_age_level', function (Blueprint $table) {
              $table->increments('id', true, true);
              $table->unsignedInteger('happ_id');
              $table->unsignedInteger('age_level_id');
              $table->foreign('age_level_id')
                    ->references('id')->on('age_level')
                    ->onUpdate('cascade');
              $table->foreign('happ_id')
                      ->references('id')->on('events')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');
              $table->timestamps();
          }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('happ_age_level');
    }
}
