<?php
namespace CityHapps\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use JWTAuth;

use CityHapps\SiteSetting;

class AdminWelcomeMessageController extends Controller {

  /**
   * Display a listing of the resource.
   * GET /admin/venue
   *
   * @return Response
   */
  public function show()
  {
      $this->getAuthUser();
    //show the admin frontend view
      return ['welcome_message' => SiteSetting::findKey('welcome_message')->value];
  }

  public function update(Request $req) {
      $this->getAuthUser();
      $setting = SiteSetting::setKey('welcome_message', $req->get('welcome_message'));
      return ['welcome_message' => $setting->value];
  }

  public function create()
  {
    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to create venue';
    $venueParams = array();

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
    public function getAuthUser()
    {
    	$user = false;

    	// Check if the token is set, and if so try to parse it
    	if (JWTAuth::getToken()) {
	        try {
	        	if ($user = JWTAuth::parseToken()->authenticate()) {
                    Log::Info('AdminWelcomeMessageController::getAuthUser. Auth::login.');
                    \Auth::login($user, true);
                    return $user;
                } else {
                    throw new \Exception("Invalid Token");
                }
	        } catch (Exception $e) {
	        	return response()->json(['token_invalid'], 403);
	        }
	    } else {
            return response()->json(['token_invalid'], 403);
        }
    }

}
