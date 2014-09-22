<?php 

class SessionsController extends BaseController {
	
	public function create() {

		if (Auth::check()) {
			return Redirect::to('/');	
		}

		return Redirect::to('/');

	}

	public function store() {

		if (Auth::attempt(Input::only('email', 'password'))) {
			
			return Redirect::to('/admin');

		}
		
		return "Failed";
	}

	public function destroy() {
		Auth::logout();

		return Redirect::route('sessions.create');
	}
}



?>