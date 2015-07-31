<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetupCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetupCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->string('name');
			$table->string('long_name');
			$table->integer('source_category_id');
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
		Schema::drop('meetupCategories');
	}

}
