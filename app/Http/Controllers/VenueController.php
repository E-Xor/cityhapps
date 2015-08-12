<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use CityHapps\Venue;

class VenueController extends Controller {

	public static function storeVenues()
    {
		Venue::storeVenues();

		return true;
	}


  public function venues() {
    $params['id'] = Input::get('id');
    $params['name'] = Input::get('name');
    $params['venueAddress'] = Input::get('venue_address');
    $params['venueCity'] = Input::get('venue_city');
    $params['venueState'] = Input::get('venue_state');
    $params['venueZip'] = Input::get('venue_zip');
    $params['description'] = Input::get('description');
    $params['latitude'] = Input::get('latitude');
    $params['longitude'] = Input::get('longitude');
    $params['createdAt'] = Input::get('created_at');
    $params['updatedAt'] = Input::get('updated_at');
    $params['source'] = Input::get('source');
    $params['imageRequired'] = Input::get('image_required');
    $params['pageSize'] = Input::get('page_size', 100);
    $params['page'] = Input::get('page', 1);

    // We'll use this for proper queries later
    //$venues = Venue::selectVenues($params);
    
    if (!empty($params['id'])) {
      $_tags = Venue::with('tags');
      $venues = array($_tags->find($params['id']));
    }
    else {
      $venuesPaginator = Venue::paginate($params['pageSize']);
      $venues = $venuesPaginator->getItems();
    }

    $meta = array();
    $meta['count'] = Venue::count();
    $meta['page'] = $params['page'];

    $results = array("meta" => $meta, "venues" => $venues);

    return $results;
  }


}
