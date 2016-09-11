<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessIdToUsers extends Migration
{

  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->integer('business_id')->nullable();
    });

  }

  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('business_id');
    });
  }

}
