<?php

class MeetupVenues extends Integration {

	protected $table = 'meetup_venues';

	public static function storeVenues($venueParams) {
		$response = '';

		// Build out the JSON venue request parameters
		$venueParams['page_size'] = empty($venueParams['page_size']) ? 200 : $venueParams['page_size'];
		$offset = ($venueParams['page_number'] != null) ? (int) $venueParams['page_number'] - 1 : 0;

		$values = array(
			'page' => $venueParams['page_size'],
			'offset' => $offset,
		);

		// Make the JSON Request
		$jsonResult = MeetupVenues::retrieveData('meetup', 'venues', $values);

        $total = count($jsonResult['results']);
		$response = ceil($jsonResult['meta']['total_count'] / $venueParams['page_size']);
	
		for ($i = 0; $i < $total; $i++ ) {
			
			$checkExisting = MeetupVenues::where('meetupVenueID', '=', $jsonResult['results'][$i]['id']);
			$venueRecords = $checkExisting->get();
			
			if ($venueRecords->count() < 1) {
				$venueRecords->push(new MeetupVenues());
			}

			foreach ($venueRecords as $venueRecord) {

                if (isset($jsonResult['results'][$i]['id'])) {
                    $venueRecord->meetupVenueID = $jsonResult['results'][$i]['id'];
                }
                if (isset($jsonResult['results'][$i]['zip'])) {
                    $venueRecord->zip = $jsonResult['results'][$i]['zip'];
                }
                if (isset($jsonResult['results'][$i]['country'])) {
                    $venueRecord->country = $jsonResult['results'][$i]['country'];
                }
                if (isset($jsonResult['results'][$i]['distance'])) {
                    $venueRecord->distance = $jsonResult['results'][$i]['distance'];
                }
                if (isset($jsonResult['results'][$i]['city'])) {
                    $venueRecord->city = $jsonResult['results'][$i]['city'];
                }
                if (isset($jsonResult['results'][$i]['address_1'])) {
                    $venueRecord->address_1 = $jsonResult['results'][$i]['address_1'];
                }
                if (isset($jsonResult['results'][$i]['address_2'])) {
                    $venueRecord->address_2 = $jsonResult['results'][$i]['address_2'];
                }
                if (isset($jsonResult['results'][$i]['address_3'])) {
                    $venueRecord->address_3 = $jsonResult['results'][$i]['address_3'];
                }
                if (isset($jsonResult['results'][$i]['phone'])) {
                    $venueRecord->phone = $jsonResult['results'][$i]['phone'];
                }
                if (isset($jsonResult['results'][$i]['rating'])) {
                    $venueRecord->rating = $jsonResult['results'][$i]['rating'];
                }
                if (isset($jsonResult['results'][$i]['lon'])) {
                    $venueRecord->lon = $jsonResult['results'][$i]['lon'];
                }
                if (isset($jsonResult['results'][$i]['name'])) {
                    $venueRecord->name = $jsonResult['results'][$i]['name'];
                }
                if (isset($jsonResult['results'][$i]['state'])) {
                    $venueRecord->state = $jsonResult['results'][$i]['state'];
                }
                if (isset($jsonResult['results'][$i]['lat'])) {
                    $venueRecord->lat = $jsonResult['results'][$i]['lat'];
                }
                if (isset($jsonResult['results'][$i]['rating_count'])) {
                    $venueRecord->rating_count = $jsonResult['results'][$i]['rating_count'];
                }
                if (isset($jsonResult['results'][$i]['email'])) {
                    $venueRecord->email = $jsonResult['results'][$i]['email'];
                }
                if (isset($jsonResult['results'][$i]['fax'])) {
                    $venueRecord->fax = $jsonResult['results'][$i]['fax'];
                }
                if (isset($jsonResult['results'][$i]['taglist'])) {
                    $venueRecord->taglist = $jsonResult['results'][$i]['taglist'];
                }
                if (isset($jsonResult['results'][$i]['venue_url'])) {
                    $venueRecord->venue_url = $jsonResult['results'][$i]['venue_url'];
                }
                if (isset($jsonResult['results'][$i]['venue_id'])) {
                    $venueRecord->venue_url = $jsonResult['results'][$i]['venue_id'];
                }

                try {
                    $venueRecord->save();
                } catch (Exception $e) {
                    print_r("Line: " . $e->getLine() ."Message: ".$e->getMessage());
                }
			}
		}
		return $response;
	}
}
