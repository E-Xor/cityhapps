<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetupVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetup_venues', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('meetupVenueID');
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('distance')->nullable();
            $table->string('city')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('address_3')->nullable();
            $table->string('phone')->nullable();
            $table->string('rating')->nullable();
            $table->string('lon')->nullable();
            $table->string('rating_count')->nullable();
            $table->string('name')->nullable();
            $table->string('state')->nullable();
            $table->string('lat')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('taglist')->nullable();
            $table->string('venue_url')->nullable();
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
		Schema::drop('meetup_venues');
	}

}
