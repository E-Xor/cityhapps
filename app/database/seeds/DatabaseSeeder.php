<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('CategoryTableSeeder');
	}

}

class CategoryTableSeeder extends Seeder {

	public function run()
	{
		DB::table('categories')->delete();

		Category::create(array('name' => 'Sports'));
		Category::create(array('name' => 'Family'));
		Category::create(array('name' => 'Educational'));
		Category::create(array('name' => 'Sewing'));
		Category::create(array('name' => 'Food'));
		Category::create(array('name' => 'Patriotic'));
		Category::create(array('name' => 'Misc'));
	}
}
