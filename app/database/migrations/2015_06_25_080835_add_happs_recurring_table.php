<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHappsRecurringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('happs_recurring', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('happ_id')->unsigned(); // Happ table ID
			$table->dateTime('start_date'); // When does this event starts, this refers to the recurring cycle?
			$table->dateTime('end_date')->nullable(); // When does this event ends, NULLS means it has no pre-defined end
			$table->integer('frequency'); //Every how many days will this event occurs again

		});

		Schema::table('happs', function(Blueprint $table)
		{
			$table->integer("is_recurring")->default(0);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('happs_recurring');

		Schema::table('happs', function(Blueprint $table)
		{
			$table->dropColumn('is_recurring');
		});
	}

}
