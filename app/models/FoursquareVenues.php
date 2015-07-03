<?php

class FoursquareVenues extends Integration {

	protected $table = 'foursquare_venues';

	public static function storeVenues($venueParams) {
		$response = '';

		// Build out the JSON venue request parameters
        $pageSize = ($venueParams['page_size'] != null) ? $venueParams['page_size'] : 100;
		$pageNumber = ($venueParams['page_number'] != null) ? $venueParams['page_number'] : 1;
        $offset = ($pageNumber - 1) * $pageSize;

		$values = array(
			'limit' => $pageSize,
			'offset' => $offset,
		);

		$jsonResponse = FoursquareVenues::retrieveData('foursquare', 'venues', $values);
        $itemsArray = $jsonResponse['response']['groups'][0]['items'];
		$total = count($itemsArray);

        $response = ceil($jsonResponse['response']['totalResults'] / $pageSize);

        for ($i = 0; $i < $total; $i++ ) {

			$checkExisting = FoursquareVenues::where('foursquareVenueID', '=', $itemsArray[$i]['venue']['id']);
			$venueRecords = $checkExisting->get();

			if ($venueRecords->count() < 1) {
				$venueRecords->push(new FoursquareVenues());
			}

			foreach ($venueRecords as $venueRecord) {

                if (isset($itemsArray[$i]['venue']['id'])) {
                    $venueRecord->foursquareVenueID = $itemsArray[$i]['venue']['id'];
                }
                if (isset($itemsArray[$i]['venue']['name'])) {
                    $venueRecord->name = $itemsArray[$i]['venue']['name'];
                }
                if (isset($itemsArray[$i]['venue']['verified'])) {
                    $venueRecord->verified = $itemsArray[$i]['venue']['verified'];
                }
                if (isset($itemsArray[$i]['venue']['url'])) {
                    $venueRecord->url = $itemsArray[$i]['venue']['url'];
                }
                if (isset($itemsArray[$i]['venue']['rating'])) {
                    $venueRecord->rating = $itemsArray[$i]['venue']['rating'];
                }
                if (isset($itemsArray[$i]['venue']['ratingColor'])) {
                    $venueRecord->ratingColor = $itemsArray[$i]['venue']['ratingColor'];
                }

                if (isset($itemsArray[$i]['venue']['ratingSignals'])) {
                    $venueRecord->ratingSignals = $itemsArray[$i]['venue']['ratingSignals'];
                }
                if (isset($itemsArray[$i]['venue']['storeId'])) {
                    $venueRecord->storeId = $itemsArray[$i]['venue']['storeId'];
                }
                if (isset($itemsArray[$i]['venue']['venuePage'])) {
                    if (isset($itemsArray[$i]['venue']['venuePage']['id'])) {
                        $venueRecord->venuePage_id = $itemsArray[$i]['venue']['venuePage']['id'];
                    }
                }

                if (isset($itemsArray[$i]['venue']['categories'])) {
                    $venueRecord->categories = serialize($itemsArray[$i]['venue']['categories']);
                }
                if (isset($itemsArray[$i]['venue']['stats'])) {
                    $venueRecord->stats = serialize($itemsArray[$i]['venue']['stats']);
                }
                if (isset($itemsArray[$i]['venue']['specials'])) {
                    $venueRecord->specials = serialize($itemsArray[$i]['venue']['specials']);
                }
                if (isset($itemsArray[$i]['venue']['photos'])) {
                    $venueRecord->photos = serialize($itemsArray[$i]['venue']['photos']);
                }
                if (isset($itemsArray[$i]['venue']['hereNow'])) {
                    $venueRecord->hereNow = serialize($itemsArray[$i]['venue']['hereNow']);
                }

                if (isset($itemsArray[$i]['venue']['contact'])) {
                    if (isset($itemsArray[$i]['venue']['contact']['phone'])) {
                        $venueRecord->contact_phone = $itemsArray[$i]['venue']['contact']['phone'];
                    }
                    if (isset($itemsArray[$i]['venue']['contact']['formattedPhone'])) {
                        $venueRecord->contact_formattedPhone = $itemsArray[$i]['venue']['contact']['formattedPhone'];
                    }
                    if (isset($itemsArray[$i]['venue']['contact']['facebook'])) {
                        $venueRecord->contact_facebook = $itemsArray[$i]['venue']['contact']['facebook'];
                    }
                    if (isset($itemsArray[$i]['venue']['contact']['facebookUsername'])) {
                        $venueRecord->contact_facebookUsername = $itemsArray[$i]['venue']['contact']['facebookUsername'];
                    }
                    if (isset($itemsArray[$i]['venue']['contact']['facebookName'])) {
                        $venueRecord->contact_facebookName = $itemsArray[$i]['venue']['contact']['facebookName'];
                    }
                    if (isset($itemsArray[$i]['venue']['contact']['twitter'])) {
                        $venueRecord->contact_twitter = $itemsArray[$i]['venue']['contact']['twitter'];
                    }
                }

                if (isset($itemsArray[$i]['venue']['location'])) {
                    if (isset($itemsArray[$i]['venue']['location']['address'])) {
                        $venueRecord->location_address = $itemsArray[$i]['venue']['location']['address'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['crossStreet'])) {
                        $venueRecord->location_crossStreet = $itemsArray[$i]['venue']['location']['crossStreet'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['lat'])) {
                        $venueRecord->location_lat = $itemsArray[$i]['venue']['location']['lat'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['lng'])) {
                        $venueRecord->location_lng = $itemsArray[$i]['venue']['location']['lng'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['postalCode'])) {
                        $venueRecord->location_postalCode = $itemsArray[$i]['venue']['location']['postalCode'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['cc'])) {
                        $venueRecord->location_cc = $itemsArray[$i]['venue']['location']['cc'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['neighborhood'])) {
                        $venueRecord->location_neighborhood = $itemsArray[$i]['venue']['location']['neighborhood'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['city'])) {
                        $venueRecord->location_city = $itemsArray[$i]['venue']['location']['city'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['state'])) {
                        $venueRecord->location_state = $itemsArray[$i]['venue']['location']['state'];
                    }
                    if (isset($itemsArray[$i]['venue']['location']['country'])) {
                        $venueRecord->location_country = $itemsArray[$i]['venue']['location']['country'];
                    }
                }

                if (isset($itemsArray[$i]['venue']['hours'])) {
                    if (isset($itemsArray[$i]['venue']['hours']['status'])) {
                        $venueRecord->hours_status = $itemsArray[$i]['venue']['hours']['status'];
                    }
                    if (isset($itemsArray[$i]['venue']['hours']['isOpen'])) {
                        $venueRecord->hours_isOpenNow = $itemsArray[$i]['venue']['hours']['isOpen'];
                    }
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