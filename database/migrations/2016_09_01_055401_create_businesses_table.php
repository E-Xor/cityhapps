<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{

  public function up()
  {
    Schema::create('businesses', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('address_1', 200);
      $table->string('address_2', 100)->nullable();
      $table->string('city', 40);
      $table->string('state', 20);
      $table->string('zip', 7);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::drop('businesses');
  }

}
