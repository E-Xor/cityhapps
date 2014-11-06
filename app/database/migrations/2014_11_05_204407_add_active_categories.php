<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		

		Schema::create('activeCategories', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id');
			$table->integer('category_id');
			$table->string('name');
			$table->string('long_name');
			$table->integer('source_category_id');
			$table->timestamps();
		});

		ActiveCategory::create(array('name' => 'classes'));
		ActiveCategory::create(array('name' => 'event'));
		ActiveCategory::create(array('name' => 'tournaments'));
		ActiveCategory::create(array('name' => 'camps'));
		ActiveCategory::create(array('name' => 'races'));
		ActiveCategory::create(array('name' => 'trail heads'));
		ActiveCategory::create(array('name' => 'leagues'));
		ActiveCategory::create(array('name' => 'articles'));
		ActiveCategory::create(array('name' => 'clinics'));
		ActiveCategory::create(array('name' => 'memberships'));
		ActiveCategory::create(array('name' => 'lessons'));
		ActiveCategory::create(array('name' => 'programs'));
		ActiveCategory::create(array('name' => 'experts'));
		ActiveCategory::create(array('name' => 'conferences'));
		ActiveCategory::create(array('name' => 'workshops'));
		ActiveCategory::create(array('name' => 'swimming pools/aquatic centers'));
		ActiveCategory::create(array('name' => 'schools'));
		ActiveCategory::create(array('name' => 'meetings'));
		ActiveCategory::create(array('name' => 'virtual events'));
		ActiveCategory::create(array('name' => 'place'));
		ActiveCategory::create(array('name' => 'training plans'));
		ActiveCategory::create(array('name' => 'creative work'));
		ActiveCategory::create(array('name' => 'clubs'));
		ActiveCategory::create(array('name' => 'tickets'));
		ActiveCategory::create(array('name' => 'meetups'));

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('activeCategories');
	}

}
