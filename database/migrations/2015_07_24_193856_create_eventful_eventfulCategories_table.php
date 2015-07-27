<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventfulEventfulCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventful_eventfulCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('eventful_id');
			$table->integer('eventfulCategories_id');
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
		Schema::drop('eventful_eventfulCategories');
	}

}
