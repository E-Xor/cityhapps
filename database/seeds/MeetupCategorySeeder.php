<?php

use Illuminate\Database\Seeder;

class MeetupCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meetupCategories')->insert([
			'long_name' => 'Arts & Culture',
			'source_category_id' => 1,
			'name' => 'Arts',
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Career & Business',
			'source_category_id' => 2,
			'name' => 'Business',
			'category_id' => DB::table('categories')->where('name', 'Business')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Cars & Motorcycles',
			'source_category_id' => 3,
			'name' => 'Auto',
			'category_id' => DB::table('categories')->where('name', 'Auto')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Community & Environment',
			'source_category_id' => 4,
			'name' => 'Community',
			'category_id' => DB::table('categories')->where('name', 'Community')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Dancing',
			'source_category_id' => 5,
			'name' => 'Dancing',
			'category_id' => DB::table('categories')->where('name', 'Nightlife')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Education & Learning',
			'source_category_id' => 6,
			'name' => 'Education',
			'category_id' => DB::table('categories')->where('name', 'Family')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Fashion & Beauty',
			'source_category_id' => 8,
			'name' => 'Fashion',
			'category_id' => DB::table('categories')->where('name', 'Fashion & Beauty')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Fitness',
			'source_category_id' => 9,
			'name' => 'Fitness',
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Food & Drink',
			'source_category_id' => 10,
			'name' => 'Food & Drink',
			'category_id' => DB::table('categories')->where('name', 'Food & Wine')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Games',
			'source_category_id' => 11,
			'name' => 'Games',
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Movements & Politics',
			'source_category_id' => 13,
			'name' => 'Movements',
			'category_id' => DB::table('categories')->where('name', 'Politics')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Health & Wellbeing',
			'source_category_id' => 14,
			'name' => 'Well-being',
			'category_id' => DB::table('categories')->where('name', 'Health')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Hobbies & Crafts',
			'source_category_id' => 15,
			'name' => 'Crafts',
			'category_id' => DB::table('categories')->where('name', 'Hobbies')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Language & Ethnic Identity',
			'source_category_id' => 16,
			'name' => 'Languages',
			'category_id' => DB::table('categories')->where('name', 'Lifestyle')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'LGBT',
			'source_category_id' => 12,
			'name' => 'LGBT',
			'category_id' => DB::table('categories')->where('name', 'Lifestyle')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Lifestyle',
			'source_category_id' => 17,
			'name' => 'Lifestyle',
			'category_id' => DB::table('categories')->where('name', 'Lifestyle')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Literature & Writing',
			'source_category_id' => 18,
			'name' => 'Literature',
			'category_id' => DB::table('categories')->where('name', 'Books')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Movies & Film',
			'source_category_id' => 20,
			'name' => 'Films',
			'category_id' => DB::table('categories')->where('name', 'Movies')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Music',
			'source_category_id' => 21,
			'name' => 'Music',
			'category_id' => DB::table('categories')->where('name', 'Music')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'New Age & Spirituality',
			'source_category_id' => 22,
			'name' => 'Spirituality',
			'category_id' => DB::table('categories')->where('name', 'Religion')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Outdoors & Adventure',
			'source_category_id' => 23,
			'name' => 'Outdoors',
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Paranormal',
			'source_category_id' => 24,
			'name' => 'Paranormal',
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Parents & Family',
			'source_category_id' => 25,
			'name' => 'Moms & Dads',
			'category_id' => DB::table('categories')->where('name', 'Family')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Pets & Animals',
			'source_category_id' => 26,
			'name' => 'Pets',
			'category_id' => DB::table('categories')->where('name', 'Pets')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Photography',
			'source_category_id' => 27,
			'name' => 'Photography',
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Religion & Beliefs',
			'source_category_id' => 28,
			'name' => 'Beliefs',
			'category_id' => DB::table('categories')->where('name', 'Religion')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Sci-Fi & Fantasy',
			'source_category_id' => 29,
			'name' => 'Sci fi',
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Singles',
			'source_category_id' => 30,
			'name' => 'Singles',
			'category_id' => DB::table('categories')->where('name', 'Nightlife')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Socializing',
			'source_category_id' => 31,
			'name' => 'Social',
			'category_id' => DB::table('categories')->where('name', 'Nightlife')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Sports & Recreation',
			'source_category_id' => 32,
			'name' => 'Sports',
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Support',
			'source_category_id' => 33,
			'name' => 'Support',
			'category_id' => DB::table('categories')->where('name', 'Health')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Tech',
			'source_category_id' => 34,
			'name' => 'Tech',
			'category_id' => DB::table('categories')->where('name', 'Science & Tech')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('meetupCategories')->insert([
			'long_name' => 'Women',
			'source_category_id' => 35,
			'name' => 'Women',
			'category_id' => DB::table('categories')->where('name', 'Lifestyle')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
    }
}
