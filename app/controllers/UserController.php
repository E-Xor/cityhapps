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
		
		$json = Input::only('email', 'password', 'categories');

		$user = new User;
		// $user->username = $json['username'];
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
