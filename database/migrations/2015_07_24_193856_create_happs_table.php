<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHappsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('happs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('url')->nullable();
			$table->string('source_id')->nullable();
			$table->string('event_name')->nullable();
			$table->string('venue_url')->nullable();
			$table->string('venue_name')->nullable();
			$table->string('address')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip')->nullable();
			$table->text('description')->nullable();
			$table->string('all_day_flag')->nullable();
			$table->text('event_image_url', 65535)->nullable();
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
			$table->timestamps();
			$table->string('source');
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->date('event_date')->nullable();
			$table->integer('upper_bound_price');
			$table->integer('lower_bound_price');
			$table->enum('type', array('Indoor','Outdoor','Mixed'));
			$table->string('serialized');
			$table->enum('gender_preference', array('Male','Female','Any'));
			$table->integer('status')->default(1);
			$table->integer('venue_id')->unsigned()->nullable();
			$table->integer('parent_id')->unsigned()->nullable();
			$table->unique(['source','source_id'], 'SOURCE_ID_UNIQUE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('happs');
	}

}
