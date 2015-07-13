<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraVenueColumnsEventbrite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eventbrite', function(Blueprint $table)
		{
			//Drop old definition, add them add again in their correct positions, and using the correct data type
			$table->dropColumn('latitude');
			$table->dropColumn('longitude');
		});

		Schema::table('eventbrite', function(Blueprint $table)
		{
			$table->float('latitude', 16, 14)->nullable()->after('postal_code');
			$table->float('longitude', 16, 14)->nullable()->after('latitude');
			$table->integer('venue_id')->unsigned()->nullable()->after('venue_name');

		});

        Schema::table('events', function(Blueprint $table)
        {
            //Composite Key to avoid dup venues
            $table->unique(array('source', 'source_id'), 'SOURCE_ID_UNIQUE');
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
			$table->dropColumn('latitude');
			$table->dropColumn('longitude');
			$table->dropColumn('venue_id');
		});

        Schema::table('events', function(Blueprint $table){
            $table->dropIndex('SOURCE_ID_UNIQUE');
        });
	}

}
