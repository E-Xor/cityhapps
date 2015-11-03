<?php

namespace CityHapps\Http\Controllers;

use Log;
use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use CityHapps\User;
use JWTAuth;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return User::all();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return "Posted a User";
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		$returnUser = '';

		//Dont Send email if Facebook user
        $request->request->get('email');

		/*\Mail::send('emails.welcome', array('key' => 'value'), function($message) use ($request){
			$message->from('team@cityhapps.com', 'CityHapps');
			$email = $request->request->get('email');
			$message->to($email, $email)->subject('Welcome to CityHapps!');
		});*/

		$json = $request->request->all();

		$user = new User;
		$user->email = $json['email'];
		$user->user_name = $json['name'];
		$user->role = User::ROLE_USER;

		if (\Input::only('password') == '') {
			$user->password = \Hash::make($json['fb_token']);
			$user->fb_token = $json['fb_token'];
			$user->save();
		} else {
			$user->password = \Hash::make($json['password']);
			$user->city = $json['city'];
			$user->save();

			$categoriesPaired = $json['categories']; // array in "categoryID": true
			if ($categoriesPaired != '') {
				$categories = array();
				foreach($categoriesPaired as $key => $value) {
					if ($value == true) {
						array_push($categories, $key);
					}
				}
				$user->categories()->sync($categories);
			}
		}

		return $user . " New User Created Successfully!";	
	}

	public function updateCategories()
	{

		$returnUser = '';

		$json = \Input::only('id', 'categories');

		$user = User::find($json['id']);

		$categoriesPaired = $json['categories']; // array in "categoryID": true

		if ($categoriesPaired != '') {

			$categories = array();

			foreach($categoriesPaired as $key => $value) {
				if ($value == true) {
					array_push($categories, $key);
				}
			}

			$user->categories()->sync($categories);
		}

	}

	public function getUserCategories()
	{

		//Checkboxes on client are fussy and need a flat 1D object

		$id = \Input::only('id');

		$user = User::with('categories')->find($id);

		$userData = (array)$user;

		$categoriesArray = array();

		foreach ($userData as $categories) {
			
			$categoryIds = $categories[0]['categories'];

			for ($i = 0; $i < count($categoryIds); $i++) {

				array_push($categoriesArray, array($categoryIds[$i]['id'] => true));	

			}	
		}

		return $categoriesArray;

	}

	/*
	 *
	 */
	public function check(Request $request)
	{
		$email = $request->request->get('value');
		$rules = array('email' => 'unique:users,email');
		$userID = User::where('email', '=', $email)->pluck('id');
		$validator = \Validator::make(array('email' => $email), $rules);
		if ($validator->fails()) {			
			echo json_encode(array(
				'isValid' 	=> false,
				'id' 		=> $userID));
			return;
		} else {
			echo json_encode(array(
				'isValid' 	=> true, 
				'value' 	=> 'nice ' . $email));
			return;
		}
	}

	/*
	 * Checks for validity of an email and returns a user ID if it exists
	 */
    public function exist(Request $request)
    {
    	$validator = \Validator::make($request->all(), [
    		'value' => 'email'
    	]);
    	if ($validator->fails()) {
    		echo json_encode(array(
    			'isValid'	=> false,
    			'id'		=> NULL,
    		));
    		return;
    	} else {
    		$userID = User::where('email', $request->request->get('value'))->pluck('id');
    		echo json_encode(array(
    			'isValid'	=> true,
    			'id'		=> $userID,
    		));
    		return;
    	}
    }

    public function changePassword(Request $request) {
        $user_params = $request->get('user');
        $user = User::find($user_params['id'])->first();

        if(\Hash::check($user_params['current_password'], $user->password)){
          if($user_params['password'] == $user_params['password_confirmation']){
            $user->password = \Hash::make($user_params['password']);
            $user->save();
          }
        }

        return;
    }

    public function resetPassword() {

        $email = \Input::only('email')['email'];

        $user = User::where('email', $email)->first();

        $password = substr(sha1($email . time()), 0, 12);

        if(!is_null($user)) {

            $user->password = \Hash::make($password);

            $user->save();

            \Mail::send('emails.reset', array('password' => $password), function($message) use ($email){

                $message->from('team@cityhapps.com', 'CityHapps');

                $message->to($email, $email)->subject('Reset Password on CityHapps!');

            });

            return json_encode(array('status' => 'ok', 'message' => 'Successfully'));
        }

        return json_encode(array('status' => 'error', 'message' => 'Have errors'));


    }

    public function getUserData(Request $request)
    {
        $userId = $request->request->get('id');
        $user = User::find($userId);

        return json_encode($user);

    }




	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
        $this->validate($request, [
        	'user_name' => 'required',
        	'email' => 'required|email',
        	'city' => 'required',
        	'password' => 'confirmed|min:6',
        ]);

        $user = FALSE;

        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return $user;
        }

        if ($user) {
            
        	$user->user_name = \Input::get('user_name');
        	$user->city = \Input::get('city');
        	$user->email = \Input::get('email');

        	if (\Input::get('password') != '') {
				$user->password = \Hash::make(\Input::get('password'));
			}

			$user->save();

		}
		return $user;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    public function checkPermission(Request $request)
    {
        $uri = $request->path();
        $domain = url();

        $user = null;

        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            //
        }

        if ($user instanceof User) {
            if($user->isAdmin()) {
                return redirect()->to($domain . '/' . $uri);
            }
        }

        return redirect()->to($domain);
    }

    /**
     * @return null || User $user
     */
    public function getAuthUser()
    {
        $user = null;

        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::parseToken()->authenticate();

            return $user;
        } catch (\Exception $e) {
            return $user;
        }

    }


}
