<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserEventTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::drop('user_event');

		Schema::create('user_event', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id');
			$table->string('event_id');
			$table->boolean('upvote')->default(false); // Event URL
			$table->string('downvote')->default(false); // Event ID (from source)
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
