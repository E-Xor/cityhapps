<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetup', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('event_url')->nullable();
			$table->string('meetupID')->nullable();
			$table->string('name')->nullable();
			$table->string('venueURL')->nullable();
			$table->string('venueName')->nullable();
			$table->string('address_1')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip')->nullable();
			$table->text('description')->nullable();
			$table->string('time')->nullable();
			$table->string('timezone')->nullable();
			$table->string('duration')->nullable();
			$table->string('AllDayFlag')->nullable();
			$table->string('photo_url')->nullable();
			$table->string('lat')->nullable();
			$table->string('lon')->nullable();
			$table->timestamps();
			$table->string('utc_offset')->nullable();
			$table->integer('venue_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('meetup');
	}

}
