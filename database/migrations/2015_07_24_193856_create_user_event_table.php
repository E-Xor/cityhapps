<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserEventTable extends Migration {

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
			$table->boolean('vote');
			$table->timestamps();
			$table->integer('event_id')->unique('user_event_event_id_user_id_unique');
			$table->integer('user_id')->unsigned();
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
