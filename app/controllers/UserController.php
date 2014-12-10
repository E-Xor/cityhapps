<?php

class UserController extends \BaseController {

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
	public function store()
	{

		$returnUser = '';

		//Dont Send email if Facebook user
		Input::get('email'); 

		Mail::send('emails.welcome', array('key' => 'value'), function($message){

			$message->from('team@cityhapps.com', 'CityHapps');

			$email = Input::get('email');
			
			$message->to($email, $email)->subject('Welcome to CityHapps!');

		});

		$json = Input::only('email', 'password', 'categories', 'fb_token', 'name');

		if (Input::only('password') == '') {

			$fb_user = new User;

			$fb_user->email  = $json['email'];
			$fb_user->password = Hash::make($json['fb_token']);
			$fb_user->fb_token = $json['fb_token'];
			$fb_user->user_name = $json['name'];

			$fb_user->save();

		} else {


			$user = new User;

			$user->email = $json['email'];
			$user->password = Hash::make($json['password']);
			$user->save();
			
			$userID = $user["id"];

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

		if ($user === 'undefined') {
			return $fb_user . " New FB User Created Successfully";
		} else {
			return $user . " New User Created Successfully!";	
		}
	}


	public function updateCategories()
	{

		$returnUser = '';

		$json = Input::only('id', 'categories');

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

		$id = Input::only('id');

		$user = User::with('categories')->find($id);

		return json_encode($user);

	}


	public function check() 
	{
		//Add Laravel email validation check

		$email = Input::only('email');
		$rules = array('email' => 'unique:users,email');
		
		$userID = DB::table('users')->where('email', $email)->pluck('id');

		$validator = Validator::make($email, $rules);

		if ($validator->fails()) {
			
			echo json_encode(array('isValid' => false,
									'id' => $userID ));
			return;

		} else {
			echo json_encode(array('isValid' => true, 
									'value' => 'nice'));
			return;
		}


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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$user = User::find($id);

		$user->password = Hash::make(Input::get('password'));

		$user->save();

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


}
