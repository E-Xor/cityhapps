<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
			'name' => 'Editor\'s Choice',
			'slug' => 'editors-choice',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
        DB::table('categories')->insert([
			'name' => 'Arts',
			'slug' => 'arts',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Comedy',
			'slug' => 'comedy',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Music',
			'slug' => 'music',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Meetups',
			'slug' => 'meetups',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Family',
			'slug' => 'family',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Movies',
			'slug' => 'movies',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Food',
			'slug' => 'food',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Charity',
			'slug' => 'charity',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Health',
			'slug' => 'health',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Seasonal',
			'slug' => 'seasonal',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Community',
			'slug' => 'community',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Sports',
			'slug' => 'sports',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Science & Tech',
			'slug' => 'science-tech',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Religion',
			'slug' => 'religion',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Politics',
			'slug' => 'politics',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Fashion & Beauty',
			'slug' => 'fashion-beauty',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Lifestyle',
			'slug' => 'lifestyle',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Hobbies',
			'slug' => 'hobbies',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Books',
			'slug' => 'books',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Attractions',
			'slug' => 'attractions',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Pets',
			'slug' => 'pets',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Auto',
			'slug' => 'auto',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('categories')->insert([
			'name' => 'Other',
			'slug' => 'other',
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
    }
}
