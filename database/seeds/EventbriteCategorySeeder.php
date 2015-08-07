<?php

use Illuminate\Database\Seeder;

class EventbriteCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eventbriteCategories')->insert([
			'name' => 'Music',
			'long_name' => 'Music',
			'source_category_id' => 103,
			'category_id' => DB::table('categories')->where('name', 'Music')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Business',
			'long_name' => 'Business & Professional',
			'source_category_id' => 101,
			'category_id' => DB::table('categories')->where('name', 'Business')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Food & Drink',
			'long_name' => 'Food & Drink',
			'source_category_id' => 110,
			'category_id' => DB::table('categories')->where('name', 'Food & Wine')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Community',
			'long_name' => 'Community & Culture',
			'source_category_id' => 113,
			'category_id' => DB::table('categories')->where('name', 'Community')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Arts',
			'long_name' => 'Performing & Visual Arts',
			'source_category_id' => 105,
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Film & Media',
			'long_name' => 'Film, Media & Entertainment',
			'source_category_id' => 104,
			'category_id' => DB::table('categories')->where('name', 'Movies')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Sports & Fitness',
			'long_name' => 'Sports & Fitness',
			'source_category_id' => 108,
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Health',
			'long_name' => 'Health & Wellness',
			'source_category_id' => 107,
			'category_id' => DB::table('categories')->where('name', 'Health')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Science & Tech',
			'long_name' => 'Science & Technology',
			'source_category_id' => 102,
			'category_id' => DB::table('categories')->where('name', 'Science & Tech')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Travel & Outdoor',
			'long_name' => 'Travel & Outdoor',
			'source_category_id' => 109,
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Charity & Causes',
			'long_name' => 'Charity & Causes',
			'source_category_id' => 111,
			'category_id' => DB::table('categories')->where('name', 'Charity')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Spirituality',
			'long_name' => 'Religion & Spirituality',
			'source_category_id' => 114,
			'category_id' => DB::table('categories')->where('name', 'Religion')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Family & Education',
			'long_name' => 'Family & Education',
			'source_category_id' => 115,
			'category_id' => DB::table('categories')->where('name', 'Family')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Holiday',
			'long_name' => 'Seasonal & Holiday',
			'source_category_id' => 116,
			'category_id' => DB::table('categories')->where('name', 'Seasonal')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Government',
			'long_name' => 'Government & Politics',
			'source_category_id' => 112,
			'category_id' => DB::table('categories')->where('name', 'Politics')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Fashion',
			'long_name' => 'Fashion & Beauty',
			'source_category_id' => 106,
			'category_id' => DB::table('categories')->where('name', 'Fashion & Beauty')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Home & Lifestyle',
			'long_name' => 'Home & Lifestyle',
			'source_category_id' => 117,
			'category_id' => DB::table('categories')->where('name', 'Lifestyle')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Auto, Boat & Air',
			'long_name' => 'Auto, Boat & Air',
			'source_category_id' => 118,
			'category_id' => DB::table('categories')->where('name', 'Auto')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Hobbies',
			'long_name' => 'Hobbies & Special Interest',
			'source_category_id' => 119,
			'category_id' => DB::table('categories')->where('name', 'Hobbies')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventbriteCategories')->insert([
			'name' => 'Other',
			'long_name' => 'Other',
			'source_category_id' => 199,
			'category_id' => DB::table('categories')->where('name', 'Other')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
    }
}
