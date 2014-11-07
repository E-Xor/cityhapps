<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryFkeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$Arts = Category::where('name', '=', 'Arts, Creative, Culture')->first();
		$Business = Category::where('name', '=', 'Business & Networking')->first();
		$Comedy = Category::where('name', '=', 'Comedy')->first();
		$Concerts = Category::where('name', '=', 'Concerts & Music')->first();
		$Organizations = Category::where('name', '=', 'Organizations & Meetups')->first();
		$Family = Category::where('name', '=', 'Family & Education')->first();
		$Movies = Category::where('name', '=', 'Movies & Film')->first();
		$Food = Category::where('name', '=', 'Food & Wine')->first();
		$Charity = Category::where('name', '=', 'Charity')->first();
		$Health = Category::where('name', '=', 'Health & Wellness')->first();
		$Holiday = Category::where('name', '=', 'Holiday & Seasonal')->first();
		$Community = Category::where('name', '=', 'Community & Culture')->first();
		$Sports = Category::where('name', '=', 'Sports & Outdoors')->first();
		$Science = Category::where('name', '=', 'Science & Technology')->first();
		$Religion = Category::where('name', '=', 'Religion & Spirituality')->first();
		$Politics = Category::where('name', '=', 'Politics & Government')->first();
		$Nightlife = Category::where('name', '=', 'Nightlife & Singles')->first();
		$Fashion = Category::where('name', '=', 'Fashion & Beauty')->first();
		$Home = Category::where('name', '=', 'Home & Lifestyle')->first();
		$Hobbies = Category::where('name', '=', 'Hobbies & Crafts')->first();
		$Literature = Category::where('name', '=', 'Literature & Books')->first();
		$Museums = Category::where('name', '=', 'Museums & Attractions')->first();
		$Pets = Category::where('name', '=', 'Pets & Animals')->first();
		$Auto = Category::where('name', '=', 'Auto, Boat & Air')->first();
		$Other = Category::where('name', '=', 'Other')->first();

		DB::table('eventfulCategories')->where('name', '=', 'animals')->update(array('category_id' => $Pets->id));
		DB::table('eventfulCategories')->where('name', '=', 'art')->update(array('category_id' => $Arts->id));
		DB::table('eventfulCategories')->where('name', '=', 'attractions')->update(array('category_id' => $Museums->id));
		DB::table('eventfulCategories')->where('name', '=', 'books')->update(array('category_id' => $Literature->id));
		DB::table('eventfulCategories')->where('name', '=', 'business')->update(array('category_id' => $Business->id));
		DB::table('eventfulCategories')->where('name', '=', 'clubs_associations')->update(array('category_id' => $Organizations->id));
		DB::table('eventfulCategories')->where('name', '=', 'comedy')->update(array('category_id' => $Comedy->id));
		DB::table('eventfulCategories')->where('name', '=', 'community')->update(array('category_id' => $Community->id));
		DB::table('eventfulCategories')->where('name', '=', 'conference')->update(array('category_id' => $Organizations->id));
		DB::table('eventfulCategories')->where('name', '=', 'family_fun_kids')->update(array('category_id' => $Family->id));
		DB::table('eventfulCategories')->where('name', '=', 'festivals_parades')->update(array('category_id' => $Concerts->id));
		DB::table('eventfulCategories')->where('name', '=', 'food')->update(array('category_id' => $Food->id));
		DB::table('eventfulCategories')->where('name', '=', 'fundraisers')->update(array('category_id' => $Charity->id));
		DB::table('eventfulCategories')->where('name', '=', 'holiday')->update(array('category_id' => $Holiday->id));
		DB::table('eventfulCategories')->where('name', '=', 'learning_education')->update(array('category_id' => $Family->id));
		DB::table('eventfulCategories')->where('name', '=', 'movies_film')->update(array('category_id' => $Movies->id));
		DB::table('eventfulCategories')->where('name', '=', 'music')->update(array('category_id' => $Concerts->id));
		DB::table('eventfulCategories')->where('name', '=', 'other')->update(array('category_id' => $Other->id));
		DB::table('eventfulCategories')->where('name', '=', 'outdoors_recreation')->update(array('category_id' => $Sports->id));
		DB::table('eventfulCategories')->where('name', '=', 'performing_arts')->update(array('category_id' => $Arts->id));
		DB::table('eventfulCategories')->where('name', '=', 'politics_activism')->update(array('category_id' => $Politics->id));
		DB::table('eventfulCategories')->where('name', '=', 'religion_spirituality')->update(array('category_id' => $Religion->id));
		DB::table('eventfulCategories')->where('name', '=', 'sales')->update(array('category_id' => $Business->id));
		DB::table('eventfulCategories')->where('name', '=', 'schools_alumni')->update(array('category_id' => $Family->id));
		DB::table('eventfulCategories')->where('name', '=', 'science')->update(array('category_id' => $Science->id));
		DB::table('eventfulCategories')->where('name', '=', 'singles_social')->update(array('category_id' => $Nightlife->id));
		DB::table('eventfulCategories')->where('name', '=', 'sports')->update(array('category_id' => $Sports->id));
		DB::table('eventfulCategories')->where('name', '=', 'support')->update(array('category_id' => $Health->id));
		DB::table('eventfulCategories')->where('name', '=', 'technology')->update(array('category_id' => $Science->id));

		DB::table('activeCategories')->where('name', '=', 'classes')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'event')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'tournaments')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'camps')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'races')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'trail heads')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'leagues')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'articles')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'clinics')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'memberships')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'lessons')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'programs')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'experts')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'conferences')->update(array('category_id' => $Organizations->id));
		DB::table('activeCategories')->where('name', '=', 'workshops')->update(array('category_id' => $Organizations->id));
		DB::table('activeCategories')->where('name', '=', 'swimming pools\/aquatic centers')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'schools')->update(array('category_id' => $Family->id));
		DB::table('activeCategories')->where('name', '=', 'meetings')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'virtual events')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'place')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'training plans')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'creative work')->update(array('category_id' => $Arts->id));
		DB::table('activeCategories')->where('name', '=', 'clubs')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'tickets')->update(array('category_id' => $Sports->id));
		DB::table('activeCategories')->where('name', '=', 'meetups')->update(array('category_id' => $Organizations->id));

		DB::table('meetupCategories')->where('name', '=', 'Arts')->update(array('category_id' => $Arts->id));
		DB::table('meetupCategories')->where('name', '=', 'Auto')->update(array('category_id' => $Auto->id));
		DB::table('meetupCategories')->where('name', '=', 'Beliefs')->update(array('category_id' => $Religion->id));
		DB::table('meetupCategories')->where('name', '=', 'Business')->update(array('category_id' => $Business->id));
		DB::table('meetupCategories')->where('name', '=', 'Community')->update(array('category_id' => $Community->id));
		DB::table('meetupCategories')->where('name', '=', 'Crafts')->update(array('category_id' => $Hobbies->id));
		DB::table('meetupCategories')->where('name', '=', 'Dancing')->update(array('category_id' => $Nightlife->id));
		DB::table('meetupCategories')->where('name', '=', 'Education')->update(array('category_id' => $Family->id));
		DB::table('meetupCategories')->where('name', '=', 'Fashion')->update(array('category_id' => $Fashion->id));
		DB::table('meetupCategories')->where('name', '=', 'Films')->update(array('category_id' => $Movies->id));
		DB::table('meetupCategories')->where('name', '=', 'Fitness')->update(array('category_id' => $Sports->id));
		DB::table('meetupCategories')->where('name', '=', 'Food & Drink')->update(array('category_id' => $Food->id));
		DB::table('meetupCategories')->where('name', '=', 'Games')->update(array('category_id' => $Sports->id));
		DB::table('meetupCategories')->where('name', '=', 'Languages')->update(array('category_id' => $Home->id));
		DB::table('meetupCategories')->where('name', '=', 'LGBT')->update(array('category_id' => $Home->id));
		DB::table('meetupCategories')->where('name', '=', 'Lifestyle')->update(array('category_id' => $Home->id));
		DB::table('meetupCategories')->where('name', '=', 'Literature')->update(array('category_id' => $Literature->id));
		DB::table('meetupCategories')->where('name', '=', 'Moms & Dads')->update(array('category_id' => $Family->id));
		DB::table('meetupCategories')->where('name', '=', 'Movements')->update(array('category_id' => $Politics->id));
		DB::table('meetupCategories')->where('name', '=', 'Music')->update(array('category_id' => $Concerts->id));
		DB::table('meetupCategories')->where('name', '=', 'Outdoors')->update(array('category_id' => $Sports->id));
		DB::table('meetupCategories')->where('name', '=', 'Paranormal')->update(array('category_id' => $Science->id));
		DB::table('meetupCategories')->where('name', '=', 'Pets')->update(array('category_id' => $Pets->id));
		DB::table('meetupCategories')->where('name', '=', 'Photography')->update(array('category_id' => $Arts->id));
		DB::table('meetupCategories')->where('name', '=', 'Sci fi')->update(array('category_id' => $Science->id));
		DB::table('meetupCategories')->where('name', '=', 'Singles')->update(array('category_id' => $Nightlife->id));
		DB::table('meetupCategories')->where('name', '=', 'Social')->update(array('category_id' => $Nightlife->id));
		DB::table('meetupCategories')->where('name', '=', 'Spirituality')->update(array('category_id' => $Home->id));
		DB::table('meetupCategories')->where('name', '=', 'Sports')->update(array('category_id' => $Sports->id));
		DB::table('meetupCategories')->where('name', '=', 'Support')->update(array('category_id' => $Health->id));
		DB::table('meetupCategories')->where('name', '=', 'Tech')->update(array('category_id' => $Science->id));
		DB::table('meetupCategories')->where('name', '=', 'Well-being')->update(array('category_id' => $Health->id));
		DB::table('meetupCategories')->where('name', '=', 'Women')->update(array('category_id' => $Home->id));

		DB::table('eventbriteCategories')->where('name', '=', 'Music')->update(array('category_id' => $Concerts->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Business')->update(array('category_id' => $Business->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Food & Drink')->update(array('category_id' => $Food->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Community')->update(array('category_id' => $Community->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Arts')->update(array('category_id' => $Arts->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Film & Media')->update(array('category_id' => $Movies->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Sports & Fitness')->update(array('category_id' => $Sports->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Health')->update(array('category_id' => $Health->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Science & Tech')->update(array('category_id' => $Science->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Travel & Outdoor')->update(array('category_id' => $Sports->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Charity & Causes')->update(array('category_id' => $Charity->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Spirituality')->update(array('category_id' => $Religion->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Family & Education')->update(array('category_id' => $Family->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Holiday')->update(array('category_id' => $Holiday->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Government & Politics')->update(array('category_id' => $Politics->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Fashion & Beauty')->update(array('category_id' => $Fashion->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Home & Lifestyle')->update(array('category_id' => $Home->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Auto, Boat & Air')->update(array('category_id' => $Auto->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Hobbies & Special Interest')->update(array('category_id' => $Hobbies->id));
		DB::table('eventbriteCategories')->where('name', '=', 'Other')->update(array('category_id' => $Other->id));	

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
