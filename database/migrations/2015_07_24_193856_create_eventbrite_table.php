<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventbriteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventbrite', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('url')->nullable();
			$table->string('eventbriteID')->nullable();
			$table->string('name_text')->nullable();
			$table->string('venue_resource_uri')->nullable();
			$table->string('venue_name')->nullable();
			$table->integer('venue_id')->unsigned()->nullable();
			$table->string('address_1')->nullable();
			$table->string('city')->nullable();
			$table->string('region')->nullable();
			$table->string('postal_code')->nullable();
			$table->float('latitude', 16, 14)->nullable();
			$table->float('longitude', 16, 14)->nullable();
			$table->text('description_text')->nullable();
			$table->string('start_local')->nullable();
			$table->string('end_local')->nullable();
			$table->string('AllDayFlag')->nullable();
			$table->text('logo_url', 65535)->nullable();
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
		Schema::drop('eventbrite');
	}

}
