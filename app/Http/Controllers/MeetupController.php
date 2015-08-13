<?php 

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;

class MeetupController extends Controller { 

	public function events() {

		// (See http://www.meetup.com/meetup_api/)
		// $app_key = '45246f1914773661d4c48611911505b';

		$event = file_get_contents('https://api.meetup.com/2/open_events.json?city=atlanta&state=GA&country=US&key=45246f1914773661d4c48611911505b');

		return $event;
	}

	public function storeEvents() {
		
		$response = '';

		$eventParams = array();

		$eventParams['page_size'] = '50';
		$eventParams['page_number'] = '1';

		$totalResults = Meetup::storeEvents($eventParams);
		$pageCount = null;

		$response .= "Total results: " . (string)$totalResults . "<br />";

		if ($totalResults != null) {
			$pageCount = Ceil((int)$totalResults / 50);
		}

		$response .= "Page count: " . (string)$pageCount . "<br />";

		if ($pageCount != null) {
			if ((int)$pageCount > 1) {
				for ($i = 2; $i < (int)$pageCount; $i++) {
					$eventParams['page_number'] = $i;
					$newTotalResults = Meetup::storeEvents($eventParams);

					$response .= $i . "<br />";

					sleep(5);
				}
			}
		}

		echo $response;
	}

}
	



?>