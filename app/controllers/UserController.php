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


		Mail::send('emails.welcome', array('key' => 'value'), function($message){

			$message->from('team@cityhapps.com', 'CityHapps');

			$email = Input::get('email');

			// return $email;

			$message->to($email, $email)->subject('Welcome to CityHapps!');
			//$json['email'], $json['email']
		});

		
		$json = Input::only('email', 'password', 'categories');

		$user = new User;

		$user->email = $json['email'];
		$user->password = Hash::make($json['password']);
		$user->save();
		
		$userID = $user["id"];



		/*
		$categoryJson = Input::json()->only('category_name', 'user_id');
		$user_category =  new User_Category;

		$user_category->name = $categoryJson['category_name'];
		$user_category->user_id = $user['id'];
		*/

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

		return $user . " New User Created Successfully!";

	}

	public function check() 
	{
		//Add Laravel email validation check

		$email = Input::only('email');
		$rules = array('email' => 'unique:users,email');

		$validator = Validator::make($email, $rules);

		if ($validator->fails()) {
			echo json_encode(array('isValid' => false,
									'value' => 'oops'));
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
		//
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
