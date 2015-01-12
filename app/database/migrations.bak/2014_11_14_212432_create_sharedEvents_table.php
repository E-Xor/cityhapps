<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sharedEvents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id');
			$table->integer('user_id');
			$table->string('share_link_key');
			$table->string('share_target_platform');
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
		Schema::drop('sharedEvents');
	}

}
