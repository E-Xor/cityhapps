<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventfulCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventfulCategories', function(Blueprint $table)
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

		EventfulCategory::create(array('name' => 'animals'));
		EventfulCategory::create(array('name' => 'art'));
		EventfulCategory::create(array('name' => 'attractions'));
		EventfulCategory::create(array('name' => 'books'));
		EventfulCategory::create(array('name' => 'business'));
		EventfulCategory::create(array('name' => 'clubs_associations'));
		EventfulCategory::create(array('name' => 'comedy'));
		EventfulCategory::create(array('name' => 'community'));
		EventfulCategory::create(array('name' => 'conference'));
		EventfulCategory::create(array('name' => 'family_fun_kids'));
		EventfulCategory::create(array('name' => 'festivals_parades'));
		EventfulCategory::create(array('name' => 'food'));
		EventfulCategory::create(array('name' => 'fundraisers'));
		EventfulCategory::create(array('name' => 'holiday'));
		EventfulCategory::create(array('name' => 'learning_education'));
		EventfulCategory::create(array('name' => 'movies_film'));
		EventfulCategory::create(array('name' => 'music'));
		EventfulCategory::create(array('name' => 'other'));
		EventfulCategory::create(array('name' => 'outdoors_recreation'));
		EventfulCategory::create(array('name' => 'performing_arts'));
		EventfulCategory::create(array('name' => 'politics_activism'));
		EventfulCategory::create(array('name' => 'religion_spirituality'));
		EventfulCategory::create(array('name' => 'sales'));
		EventfulCategory::create(array('name' => 'schools_alumni'));
		EventfulCategory::create(array('name' => 'science'));
		EventfulCategory::create(array('name' => 'singles_social'));
		EventfulCategory::create(array('name' => 'sports'));
		EventfulCategory::create(array('name' => 'support'));
		EventfulCategory::create(array('name' => 'technology'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eventfulCategories');
	}

}
