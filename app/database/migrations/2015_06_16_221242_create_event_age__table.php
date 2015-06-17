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
        Schema::create('event_age', function (Blueprint $table) {
              $table->increments('id', true, true);
              $table->unsignedInteger('event_id');
              $table->unsignedInteger('age_type_id');
              $table->foreign('event_id')
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
        Schema::drop('event_age');
    }
}
