<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id'); // Event ID (auto)
			$table->string('urlAdr'); // Event URL
			$table->string('assetGuid'); // Event ID (from source)
			$table->string('assetName'); // Event Name
			$table->string('placeUrlAdr'); // Event Venue URL
			$table->string('placeName'); // Event Venue Name
			$table->string('addressLine1Txt'); // Event Location Address
			$table->string('cityName'); // Event Location City
			$table->string('stateProvinceCode'); // Event Location State
			$table->string('postalCode'); // Event Location Zip
			$table->string('description'); // Event Description
			$table->string('activityStartDate'); // Event Start Date/Time
			$table->string('activityEndDate'); // Event End Date/Time
			$table->string('AllDayFlag'); // Event All Day Flag
			$table->string('imageUrlAdr'); // Event Image
			$table->string('lat'); // Event Latitude
			$table->string('lon'); // Event Longitude
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
		Schema::drop('active');
	}

}
