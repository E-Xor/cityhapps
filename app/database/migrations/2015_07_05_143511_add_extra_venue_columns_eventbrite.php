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
			$table->float('latitude', 16, 14)->nullable()->after('postal_code');
			$table->float('longitude', 16, 14)->nullable()->after('latitude');
		});

        Schema::table('happs', function(Blueprint $table)
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
		});

        Schema::table('happs', function(Blueprint $table){
            $table->dropIndex('SOURCE_ID_UNIQUE');
        });
	}

}
