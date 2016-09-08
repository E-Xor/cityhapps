<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessIdToHapps extends Migration
{

  public function up()
  {
    Schema::table('happs', function (Blueprint $table) {
      $table->integer('business_id')->nullable();
    });

  }

  public function down()
  {
    Schema::table('happs', function (Blueprint $table) {
        $table->dropColumn('business_id');
    });
  }

}
