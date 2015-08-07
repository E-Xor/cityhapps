<?php

use Illuminate\Database\Seeder;

class EventfulCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eventfulCategories')->insert([
			'name' => 'music',
			'long_name' => 'Concerts &amp; Tour Dates',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Music')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'conference',
			'long_name' => 'Conferences &amp; Tradeshows',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Meetups')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'comedy',
			'long_name' => 'Comedy',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Comedy')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'learning_education',
			'long_name' => 'Education',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Family')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'family_fun_kids',
			'long_name' => 'Kids &amp; Family',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Family')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'festivals_parades',
			'long_name' => 'Festivals',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Music')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'movies_film',
			'long_name' => 'Film',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Movies')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'food',
			'long_name' => 'Food &amp; Wine',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Food & Wine')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'fundraisers',
			'long_name' => 'Fundraising &amp; Charity',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Charity')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'art',
			'long_name' => 'Art Galleries &amp; Exhibits',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'support',
			'long_name' => 'Health &amp; Wellness',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Health')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'holiday',
			'long_name' => 'Holiday',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Seasonal')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'books',
			'long_name' => 'Literary &amp; Books',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Books')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'attractions',
			'long_name' => 'Museums &amp; Attractions',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Attractions')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'community',
			'long_name' => 'Neighborhood',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Community')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'business',
			'long_name' => 'Business &amp; Networking',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Business')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'singles_social',
			'long_name' => 'Nightlife &amp; Singles',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Nightlife')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'schools_alumni',
			'long_name' => 'University &amp; Alumni',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Family')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'clubs_associations',
			'long_name' => 'Organizations &amp; Meetups',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Meetups')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'outdoors_recreation',
			'long_name' => 'Outdoors &amp; Recreation',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'performing_arts',
			'long_name' => 'Performing Arts',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Arts')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'animals',
			'long_name' => 'Pets',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Pets')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'politics_activism',
			'long_name' => 'Politics &amp; Activism',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Politics')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'sales',
			'long_name' => 'Sales &amp; Retail',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Business')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'science',
			'long_name' => 'Science',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Science & Tech')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'religion_spirituality',
			'long_name' => 'Religion &amp; Spirituality',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Religion')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'sports',
			'long_name' => 'Sports',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Sports')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'technology',
			'long_name' => 'Technology',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Science & Tech')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
		DB::table('eventfulCategories')->insert([
			'name' => 'other',
			'long_name' => 'Other &amp; Miscellaneous',
			'source_category_id' => 0,
			'category_id' => DB::table('categories')->where('name', 'Other')->value('id'),
			'created_at' => date('Y-m-d h:i:s'),
			'updated_at' => date('Y-m-d h:i:s'),
		]);
    }
}
