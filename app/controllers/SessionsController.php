<?php 

class SessionsController extends BaseController {
	
	public function index() {
		return Response::json(Auth::check());
	}


	public function create() {

		if (Auth::check()) {
			return Redirect::to('/admin');	
		}

		return Redirect::to('/');

	}

	public function login() {

		if (Auth::attempt(array('email' => Input::json('email'), 'password' => Input::json('password')))) {
			
			return Response::json(Auth::user());

			// return Redirect::to('/admin');
		} else {

			return Response::json(array('flash' => 'Invalid username or password'), 500);	
		}
		
		
	}

	public function logout() {

		Auth::logout();

		return Response::json(array('flash' => 'Logged Out!'));

	}
}



?>