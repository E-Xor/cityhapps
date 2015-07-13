<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagAndTagRelTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('tag_raw');
			$table->string('tag_url');
			$table->timestamps();
		});

		Schema::create('happ_tag', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('happ_id');
			$table->integer('tag_id');
			$table->timestamps();
		});

		Schema::create('venue_tag', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('venue_id');
			$table->integer('tag_id');
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
		Schema::drop('tags');
		Schema::drop('happ_tag');
		Schema::drop('venue_tag');

	}

}
