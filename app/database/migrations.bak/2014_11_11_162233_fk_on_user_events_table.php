<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FkOnUserEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_event', function(Blueprint $table)
		{
			$table->dropColumn('user_id');
		});

		Schema::table('user_event', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();


			//Seems to clash with being unique related to event_id

			// $table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_event', function(Blueprint $table)
		{
			//
		});
	}

}
