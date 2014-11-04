<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableUserEvent extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_event', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('upvote')->default(false); // Event URL
			$table->string('downvote')->default(false); // Event ID (from source) -- should this be boolean too?
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_event');
	}

}
