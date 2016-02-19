<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use CityHapps\Venue;
use CityHapps\Tag;
use CityHapps\User;

class AdminVenueController extends Controller {

  /**
   * Display a listing of the resource.
   * GET /admin/venue
   *
   * @return Response
   */
    public function __construct() {
        $this->user = $this->authFromToken();
    }

  public function index()
  {
    //show the admin frontend view
    return View::make('admin/events/home');
  }

  /**
   * Show the form for creating a new resource.
   * GET /admin/venue/add
   *
   * @return Response
   */
  public function add()
  {
    // will be shown by angular
  }

  /**
   * Show the form for creating a new resource.
   * GET /admin/venue/edit
   *
   * @return Response
   */
  public function edit()
  {
    // will be shown by angular
  }

  /**
   *
   * Store tag information for the EventEntity
   *
   * @param $entity
   * @param $tags
   * @TODO: rework this into a library
   */
    private function createTags($entity, $tags)
    {
        if (!empty($tags)) {
            //Drop previous tags for this event
            $entity->tags()->detach();
            foreach ($tags as $tag_id => $tag) {
                if (!isset($tag["id"])) {
                    $new_tag = new Tag(['tag_raw' => $tag["tag_raw"], 'tag_url' => $tag["tag_raw"]]);
                    $entity->tags()->save($new_tag);
                } else {
                    //if the tag has an id, it means it was an old saved tag and we want it back
                    $entity->tags()->attach($tag["id"]);
                }
            }
        }
    }


  public function venues() {

    $venueParams = array();

    $venueParams['venueID'] = \Input::get('id');
    $venueParams['venueName'] = \Input::get('name');
    $venueParams['url'] = \Input::get('url');
    $venueParams['address_1'] = \Input::get('venue_address');
    $venueParams['address_2'] = \Input::get('venue_address2');
    $venueParams['address_3'] = \Input::get('venue_address3');
    $venueParams['city'] = \Input::get('venue_city');
    $venueParams['state'] = \Input::get('venue_state');
    $venueParams['postal_code'] = \Input::get('venue_zip');
    $venueParams['description'] = \Input::get('description');

    $venueParams['lat'] = \Input::get('latitude');
    $venueParams['lng'] = \Input::get('longitude');
    $venueParams['category'] = \Input::get('category'); // Note that this will be an array of values called category[]
    $venueParams['createdAt'] = \Input::get('created_at');
    $venueParams['updatedAt'] = \Input::get('updated_at');
    $venueParams['source'] = \Input::get('source');
    $venueParams['image'] = \Input::get('image');
    $venueParams['pageSize'] = \Input::get('page_size');
    $venueParams['pageCount'] = \Input::get('page_count');
    $venueParams['pageShift'] = \Input::get('page_shift');
    $venueParams['maxPerDay'] = \Input::get('max_per_day');

    $venues = Venue::selectVenues($venueParams);

    $results = array("venues" => $venues);

    return json_encode($results);
  }



  /**
   * Show the form for creating a new resource.
   * POST /admin/venue/update
   *
   * @return Response
   */
  public function update()
  {

    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to update venue';
    $venueParams = array();
//    $allParams = \Input::all();
    $venueParams['id'] = \Input::get('venue_id');
    if (!$venueParams['id']) $passValidation = false;

    $result = Venue::create($venueParams);

    if (!$this->authorizeResource($result)) {
      return response()->json(['error' => 'Unauthorized', 'message' => 'Unauthorized'], 403);
    }

    $venueParams['user_id'] = $this->user->id;
    $venueParams['name'] = \Input::get('venue_name');
    $venueParams['url'] = \Input::get('venue_url');
    $venueParams['address_1'] = \Input::get('street_address');
    $venueParams['image'] = \Input::get('venue_image_url');
    // no room for building
    //$venueParams['building'] = \Input::get('building');
    $venueParams['city'] = \Input::get('city');
    $venueParams['state'] = \Input::get('state');
    $venueParams['postal_code'] = \Input::get('zip_code');
    $venueParams['description'] = \Input::get('desc');
    $venueParams['hours'] = \Input::get('hours');
    $venueParams['phone'] = \Input::get('phone');
    $venueParams['similar_venues'] = \Input::get('similar_venues_storage');

   if ($passValidation)
   {
     $result = Venue::find($venueParams['id']);

     $venueParams['user_id'] = $result->user_id ?: $this->user->id;

     $this->createTags($result, \Input::get('tags'));

     $similar = $result->similar;
     if (!empty($venueParams['similar_venues'])) {
       $similar_venues = Venue::whereIn('id', $venueParams['similar_venues'])->get();
       if ($similar_venues){
         foreach($similar_venues as $sv){
           $sv->update(array(
             'parent_id' => $venueParams['id']
           ));
         }
       }
     } else {
       foreach ($similar as $s) {
           Venue::find($s['id'])->update(array('parent_id' => NULL));
       }
     }

     unset($venueParams['similar_venues_storage']);
     unset($venueParams['similar_venues_model']);
     unset($venueParams['similar_venues']);

     if ($result) {
      // then update
      $result->update($venueParams);
      $result['updated'] = 1;
     }
   }

   if ($result)
     return json_encode($result);
   else
     return json_encode(array('error' => true, 'message'=>$message));

 }
  /**
   * Show the form for creating a new resource.
   * POST /admin/venue/create
   *
   * @return Response
   */
  public function create()
  {
    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    if (!$this->authorizeResource(null)) {
      return response()->json(['error' => 'Unauthorized', 'message' => 'Unauthorized'], 403);
    }
    $passValidation = true;
    $message = 'Failed to create venue';
    $venueParams = array();

    $venueParams['user_id'] = $this->user->id;
    $venueParams['name'] = \Input::get('venue_name');
    $venueParams['url'] = \Input::get('venue_url');
    $venueParams['address_1'] = \Input::get('street_address');
    $venueParams['image'] = \Input::get('venue_image_url');
    // no room for building
    //$venueParams['building'] = \Input::get('building');
    $venueParams['city'] = \Input::get('city');
    $venueParams['state'] = \Input::get('state');
    $venueParams['postal_code'] = \Input::get('zip_code');
    $venueParams['description'] = \Input::get('desc');
    $venueParams['hours'] = \Input::get('hours');
    $venueParams['phone'] = \Input::get('phone');

    $venueParams['source'] = "Custom";


    if ($passValidation)
      $result = Venue::create($venueParams);
      $this->createTags($result, \Input::get('tags'));

    if ($result)
      return json_encode($result);
    else
      return json_encode(array('error' => true, 'message'=>$message));

  }

    protected function authFromToken() {
        $user = parent::authFromToken();
        if ($user->role != User::ROLE_USER) {
            return $user;
        }
    }
}
