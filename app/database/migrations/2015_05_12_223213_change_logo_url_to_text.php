<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLogoUrlToText extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eventbrite', function(Blueprint $table)
		{
      DB::statement('ALTER TABLE eventbrite MODIFY COLUMN logo_url TEXT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eventbrite', function(Blueprint $table)
		{
			//
		});
	}

}
