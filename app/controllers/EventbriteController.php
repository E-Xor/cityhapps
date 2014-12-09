<?php 


class EventbriteController extends BaseController { 

	public function events() {

		// (See http://developer.eventbrite.com/)
		// $token = 'UKNDTMAVPK4A7ACVVLWF';

		$event = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?venue.city=Atlanta&venue.region=GA&venue.country=US&token=UKNDTMAVPK4A7ACVVLWF');

		return $event;
	}

	public function storeEvents() {
		$response = '';

		$eventParams = array();

		$eventParams['page_size'] = '';
		$eventParams['page_number'] = '1';

		$pageCount = Eventbrite::storeEvents($eventParams);

		if ($pageCount != null) {
			if ((int)$pageCount > 1) {
				for ($i = 2; $i <= (int)$pageCount; $i++) {
					$eventParams['page_number'] = $i;
					$newPageCount = Eventbrite::storeEvents($eventParams);

					if ($newPageCount != null) {
						if ((int)$newPageCount != (int)$pageCount) {
							$pageCount = $newPageCount;
						}
					}

					$response .= $i . "<br />";
				}
			}
		}

		echo $response;
	}

}

?>