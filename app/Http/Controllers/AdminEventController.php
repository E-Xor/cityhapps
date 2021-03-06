<?php

namespace CityHapps\Http\Controllers;

use Log;
use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Happ;
use CityHapps\Category;
use CityHapps\Tag;
use CityHapps\ResourceImageUploader;
use CityHapps\OrganizationImageUploader;
use Input;
use CityHapps\User;

class AdminEventController extends Controller {
    public function __construct() {
        $this->user = $this->authFromToken();
    }

  /**
   * Show the form for creating a new resource.
   * GET /admin/event/add
   *
   * @return Response
   */
  public function add()
  {
    // will be shown by angular
  }

  /**
   * Show the form for creating a new resource.
   * GET /admin/event/edit
   *
   * @return Response
   */
  public function edit()
  {
    // will be shown by angular
  }
  /**
   * Show the form for creating a new resource.
   * POST /admin/event/update
   *
   * @return Response
   */
  public function update()
  {
    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to update event';
    $eventParams = array();

    $eventParams['id'] = Input::get('event_id');

    $result = Happ::find($eventParams['id']);
    if (!$this->authorizeResource($result)) {
      return response()->json(['error' => 'Unauthorized', 'message' => 'Unauthorized'], 403);
    }

    $eventParams['user_id'] = $result->user_id ?: $this->user->id;

    if (!$eventParams['id'])
      $passValidation = false;

    $eventParams['event_name'] = Input::get('title');
    $eventParams['status'] = Input::get('status');
    $eventParams['url'] = Input::get('event_url');
    $eventParams['venue_id'] = intval(Input::get('venue_id'));
    $eventParams['venue_name'] = Input::get('venue_name');
    $eventParams['venue_url'] = Input::get('venue_url');
    $eventParams['address'] = Input::get('street_address');
    if (Input::get('parent_id') > 0) {
        if (!Input::get('parent')) {
            //tag was deleted, delete the parent_id too
            Happ::find($eventParams['id'])->update(array('parent_id' => NULL, 'status' => Happ::STATUS_ACTIVE));
        } else {
            $eventParams['parent_id'] = Input::get('parent_id');
            $eventParams['status'] = Happ::STATUS_DUPLICATED;
        }
    }

    // no room for building
    //$eventParams['building'] = Input::get('building');
    $eventParams['city'] = Input::get('city');
    $eventParams['state'] = Input::get('state');
    $eventParams['zip'] = Input::get('zip_code');
    $eventParams['description'] = Input::get('desc');
    /* just to explain this ternary operator a little bit
     * sets $time to unix time (if it is an invalid input, null, or not a date, it will return false)
     * $time is false: return null
     * $time is satisfactory: return date that mysql can use
     */
    $eventParams['event_date'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d", $time));
    $eventParams['start_time'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d H:i:s", $time));
    $eventParams['all_day_flag'] = Input::get('all_day');
    $eventParams['end_time'] = (($time = strtotime(Input::get('end_time'))) === false ? null : date("Y-m-d H:i:s", $time));
    $eventParams['business_hours'] = Input::get('business_hours');

    $eventParams['similar_events'] = Input::get('similar_events_storage');

    // Location Type Data
    $location_type_data = Input::get('locationType');
    $eventParams['location_type'] = NULL;

    if (is_array($location_type_data)) {
      $indoor = false;
      $outdoor = false;
      if (isset($location_type_data['indoor']) && $location_type_data['indoor'])
        $indoor = true;
      if (isset($location_type_data['outdoor']) && $location_type_data['outdoor'])
        $outdoor = true;

      if ($indoor && !$outdoor)
        $eventParams['location_type'] = 'Indoor';
      else if (!$indoor && $outdoor)
        $eventParams['location_type'] = 'Outdoor';
    }

    $time = strtotime(Input::get('start_time'));
    $start_time = date("Y-m-d H:j:s", $time);

    if ($passValidation)
    {
      // Process Image
      if (Input::has('event_image_data')) {
          $uploader = new ResourceImageUploader(Input::get('event_image_data'), $result);
          if ($path = $uploader->save()) {
              $eventParams['event_image_url'] = $path;
          }
      }
      if (Input::has('organization_image_data')) {
          $uploader = new OrganizationImageUploader(Input::get('organization_image_data'), $result);
          if ($path = $uploader->save()) {
              $eventParams['organization_image_url'] = $path;
          }
      }

      // Process Tags
      if(Input::has('tags'))
      {
        $tags = Input::get('tags');
        $this->createTags($result, $tags);
      }
      // Process Age Levels

      if(Input::has('ageLevels'))
      {
        $age_level_data = Input::get('ageLevels');
        $result->ageLevels()->detach();
        foreach ($age_level_data as $age_level) {
          if (isset($age_level['value']) && $age_level['value'])
            $result->ageLevels()->attach($age_level['id']);
        }
      }

      // Process Categories
      if(Input::has('categories'))
      {
        $category_data = Input::get('categories');
        $result->categories()->detach();
        foreach ($category_data as $category) {
          $result->categories()->attach($category);
        }
      }

      $similar = $result->similar;
      if (!empty($eventParams['similar_events'])) {
        $similar_events = Happ::whereIn('id', $eventParams['similar_events'])->get();
        if ($similar_events) {
          foreach($similar_events as $sv) {
            $sv->update(array(
              'parent_id' => $eventParams['id'],
              'status' => Happ::STATUS_DUPLICATED
            ));
          }
        }
      } else {
        foreach ($similar as $s) {
          Happ::find($s['id'])->update(array('parent_id' => NULL, 'status' => Happ::STATUS_ACTIVE));
        }
      }

      unset($eventParams['similar_events_storage']);
      unset($eventParams['similar_events_model']);
      unset($eventParams['similar_events']);
      unset($eventParams['parent']);

     if ($result) {
      // then update
         $difference = json_encode(array_diff(array_keys($eventParams), array_keys($result->getAttributes())));
      $eventParams['serialized'] = $difference;
      $result->update($eventParams);
      $result['updated'] = 1;
     }
   }

   if ($result)
     return json_encode($result);
   else
     return json_encode(array('error' => true, 'message'=>$message));

 }


    /**
     *
     * Store tag information for the EventEntity
     *
     * @param $entity
     * @param $tags
     *
     * @TODO: rework this into a library
     */
    private function createTags($entity, $tags)
    {
        $entity->tags()->detach();
        if (!empty($tags)) {
            //Drop previous tags for this event
            foreach ($tags as $tag) {
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

  /**
   * Show the form for creating a new resource.
   * POST /admin/event/create
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
    $message = 'Failed to create event';
    $eventParams = array();

    $eventParams['user_id'] = $this->user->id;
    $eventParams['business_id'] = $this->user->business_id;
    $eventParams['event_name'] = Input::get('title');
    $eventParams['url'] = Input::get('event_url');
    $eventParams['venue_id'] = Input::get('venue_id');
    $eventParams['venue_name'] = Input::get('venue_name');
    $eventParams['venue_url'] = Input::get('venue_url');
    $eventParams['address'] = Input::get('street_address');
    // no room for building
    //$eventParams['building'] = Input::get('building');
    $eventParams['city'] = Input::get('city');
    $eventParams['state'] = Input::get('state');
    $eventParams['zip'] = Input::get('zip_code');
    $eventParams['description'] = Input::get('desc');
    /* just to explain this ternary operator a little bit
    ** sets $time to unix time (if it is an invalid input, null, or not a date, it will return false)
    ** $time is false: return null
    ** $time is satisfactory: return date that mysql can use
    */
    $eventParams['event_date'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d", $time));
    $eventParams['start_time'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d H:i:s", $time));
    $eventParams['all_day_flag'] = Input::get('all_day');
    $eventParams['end_time'] = (($time = strtotime(Input::get('end_time'))) === false ? null : date("Y-m-d H:i:s", $time));

    $eventParams['business_hours'] = Input::get('business_hours');
    // Location Type Data
    $location_type_data = Input::get('locationType');
    $eventParams['location_type'] = NULL;

    if (is_array($location_type_data)) {
      $indoor = false;
      $outdoor = false;
      if (isset($location_type_data['indoor']) && $location_type_data['indoor'])
        $indoor = true;
      if (isset($location_type_data['outdoor']) && $location_type_data['outdoor'])
        $outdoor = true;

      if ($indoor && !$outdoor)
        $eventParams['location_type'] = 'Indoor';
      else if (!$indoor && $outdoor)
        $eventParams['location_type'] = 'Outdoor';
    }

    $time = strtotime(Input::get('start_time'));
    $start_time = date("Y-m-d H:j:s", $time);
    // no spot for tags? (maybe this is keywords, and should get ran through some filtering?)
   // $eventParams['tags'] = Input::get('tags');
    $eventParams['source'] = "Custom";


    if ($passValidation) {
      $result = Happ::create($eventParams);
      //$this->dispatch(new SendEventEmail($result));

      // Process Image
      if (Input::has('event_image_data')) {
        $uploader = new ResourceImageUploader(Input::get('event_image_data'), $result);
        if ($path = $uploader->save()) {
            $result->event_image_url = $path;
            $result->save();
        }
      }
      if (Input::has('organization_image_data')) {
          $uploader = new OrganizationImageUploader(Input::get('organization_image_data'), $result);
          if ($path = $uploader->save()) {
              $result->organization_image_url = $path;
              $result->save();
          }
      }
      // Process Tags
      if(Input::exists('tags')){
        $this->createTags($result, Input::get('tags'));
      }

      // Process Categories
      if(Input::has('categories'))
      {
          $category_data = Input::get('categories');
          $result->categories()->detach();
          foreach ($category_data as $category) {
              $result->categories()->attach($category);
          }
      }
      // Process Age Levels
      $age_level_data = Input::get('ageLevels');
      $result->ageLevels()->detach();
      foreach ($age_level_data as $age_level) {
        if (isset($age_level['value']) && $age_level['value'])
          $result->ageLevels()->attach($age_level['id']);
      }
    }

    if ($result)
      return json_encode($result);
    else
      return json_encode(array('error' => true, 'message'=>$message));

  }

    protected function authFromToken() {
        $user = parent::authFromToken();
        if ($user && $user->role != User::ROLE_USER) {
            return $user;
        }
    }
}
