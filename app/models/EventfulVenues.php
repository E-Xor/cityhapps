<?php

class EventfulVenues extends Integration {

	protected $table = 'eventful_venues';

	public static function storeVenues($venueParams) {
		$response = '';

		// Build out the JSON venue request parameters
		$pageSize = ($venueParams['page_size'] != null) ? $venueParams['page_size'] : 100;
		$pageNumber = ($venueParams['page_number'] != null) ? $venueParams['page_number'] : 1;

		$values = array(
			'page_size' => $pageSize,
			'page_number' => $pageNumber,
		);

		// Make the JSON Request
		$jsonArray = EventfulVenues::retrieveData('eventful', 'venues', $values);

		$total = count($jsonArray['venues']['venue']);
		$response = $jsonArray['page_count'];
		
		for ($i = 0; $i < $total; $i++ ) {
			//one of these fields is expecting a string and geting an array

			$checkExisting = EventfulVenues::where('eventfulVenueID', '=', $jsonArray['venues']['venue'][$i]['id']);
			$venueRecords = $checkExisting->get();
			
			if ($venueRecords->count() < 1) {
				$venueRecords->push(new EventfulVenues());
			}

			foreach ($venueRecords as $venueRecord) {

                if (isset($jsonArray['venues']['venue'][$i]['id'])) {
                    $venueRecord->eventfulVenueID = $jsonArray['venues']['venue'][$i]['id'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['geocode_type'])) {
                    $venueRecord->geocode_type = $jsonArray['venues']['venue'][$i]['geocode_type'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['event_count'])) {
                    $venueRecord->event_count = $jsonArray['venues']['venue'][$i]['event_count'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['trackback_count'])) {
                    $venueRecord->trackback_count = $jsonArray['venues']['venue'][$i]['trackback_count'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['comment_count'])) {
                    $venueRecord->comment_count = $jsonArray['venues']['venue'][$i]['comment_count'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['region_abbr'])) {
                    $venueRecord->region_abbr = $jsonArray['venues']['venue'][$i]['region_abbr'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['postal_code'])) {
                    $venueRecord->postal_code = $jsonArray['venues']['venue'][$i]['postal_code'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['latitude'])) {
                    $venueRecord->latitude = $jsonArray['venues']['venue'][$i]['latitude'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['url'])) {
                    $venueRecord->url = $jsonArray['venues']['venue'][$i]['url'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['address'])) {
                    $venueRecord->address = $jsonArray['venues']['venue'][$i]['address'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['city_name'])) {
                    $venueRecord->city_name = $jsonArray['venues']['venue'][$i]['city_name'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['owner'])) {
                    $venueRecord->owner = $jsonArray['venues']['venue'][$i]['owner'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['link_count'])) {
                    $venueRecord->link_count = $jsonArray['venues']['venue'][$i]['link_count'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['country_name'])) {
                    $venueRecord->country_name = $jsonArray['venues']['venue'][$i]['country_name'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['longitude'])) {
                    $venueRecord->longitude = $jsonArray['venues']['venue'][$i]['longitude'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['timezone'])) {
                    $venueRecord->timezone = $jsonArray['venues']['venue'][$i]['timezone'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['country_abbr'])) {
                    $venueRecord->country_abbr = $jsonArray['venues']['venue'][$i]['country_abbr'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['region_name'])) {
                    $venueRecord->region_name = $jsonArray['venues']['venue'][$i]['region_name'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['country_abbr2'])) {
                    $venueRecord->country_abbr2 = $jsonArray['venues']['venue'][$i]['country_abbr2'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['name'])) {
                    $venueRecord->name = $jsonArray['venues']['venue'][$i]['name'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['description'])) {
                    $venueRecord->description = $jsonArray['venues']['venue'][$i]['description'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['image'])) {
                    $venueRecord->image = serialize($jsonArray['venues']['venue'][$i]['image']);
                }
                if (isset($jsonArray['venues']['venue'][$i]['created'])) {
                    $venueRecord->created = $jsonArray['venues']['venue'][$i]['created'];
                }
                if (isset($jsonArray['venues']['venue'][$i]['venue_type'])) {
                    $venueRecord->venue_type = $jsonArray['venues']['venue'][$i]['venue_type'];
                }
//                if (isset($jsonArray['venues']['venue'][$i]['venue_name'])) {
//                    $venueRecord->venue_name = $jsonArray['venues']['venue'][$i]['venue_name'];
//                }

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