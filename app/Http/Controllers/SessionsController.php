<?php

namespace CityHapps\Http\Controllers;

use Log;
use CityHapps\User;
use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

use Socialite;

class SessionsController extends Controller {

	public function index() {
		return \Response::json(\Auth::user());
	}


	public function create() {
		if (\Auth::check()) {
			return \Redirect::to('/admin');
		}
		return \Redirect::to('/');
	}

	public function login(Request $request) {

		$json = $request->request->all();

		$isAuth = \Auth::attempt(
							[
								'email' => $json['email'],
								'password' => $json['password']
							]
						);

		if ($isAuth) {

            $userId = User::where('email', '=', $json['email'])->pluck('id');
            $user = User::find($userId);

            \Auth::login($user, true);

			return $request->user();

			// if (Auth::viaRemember()) {
			// 	return Response::json(Auth::user());
			// } else {
			// 	return Response::json(Auth::user());
			// }

		} else {
			return \Response::json(array('flash' => 'Invalid username or password'), 500);
		}
	}

	public function logout() {
		if (\Auth::check()) {
			Log::Info('About to log out. Id: '. \Auth::user()->id .', EMail: '. \Auth::user()->email);
		} else {
			Log::Info('About to log out.');
		}

		\Auth::logout();
		Log::Info('Logged Out!');
		return \Redirect::to('/');
	}

	public function FacebookLogin() {
		Log::Info('SessionsController::FacebookLogin. Redirect');
		Log::Info("SessionsController::FacebookLogin. Auth::check(): ". \Auth::check());

	  return Socialite::driver('facebook')->redirect();
	}

	public function GoogleLogin() {
		Log::Info('SessionsController::GoogleLogin. Redirect');
		Log::Info("SessionsController::GoogleLogin. Auth::check(): ". \Auth::check());

	  return Socialite::driver('google')->redirect();
	}

	public function FacebookCallback() {
		Log::Info('SessionsController::FacebookCallback');
		Log::Info('SessionsController::FacebookCallback. Auth::check(): '. \Auth::check());

		$fbUser = Socialite::driver('facebook')->user();
		// var_export($user, true);
		// Laravel\Socialite\Two\User::__set_state(array(
		//    'token' => 'EAAGa0D4ZBs5EBAPcvch1BoM4GV9rXMStVFMZB6KsljiTHBLM8O0IZALF1FZCn4YNq9TnXjkKKmhT33aA4MNXZBhgQn7yzcgGeQekzLL78LpE9HZB1rZAjIZA9lUZCISUZCBtETpLRwym7Nyd8l8fOnQtWyAgFBZAbdC7pkZD',
		//    'refreshToken' => NULL,
		//    'expiresIn' => '5182076',
		//    'id' => '10210442562406023',
		//    'nickname' => NULL,
		//    'name' => 'Maxim Sundukov',
		//    'email' => 'e-xormail@tut.by',
		//    'avatar' => 'https://graph.facebook.com/v2.6/10210442562406023/picture?type=normal',
		//    'user' =>
		//   array (
		//     'name' => 'Maxim Sundukov',
		//     'email' => 'e-xormail@tut.by',
		//     'gender' => 'male',
		//     'verified' => true,
		//     'id' => '10210442562406023',
		//   ),
		//    'avatar_original' => 'https://graph.facebook.com/v2.6/10210442562406023/picture?width=1920',
		// ))

		$token = $fbUser->token;
		Log::info('SessionsController::FacebookCallback. Token: ' . $token);

		$name = $fbUser->getName();
		Log::Info('SessionsController::FacebookCallback. Name: ' . $name);
		$email = $fbUser->getEmail();
		Log::Info('SessionsController::FacebookCallback. EMail: ' . $email);

		$user = User::where('email', '=', $email)->first();

		if(!$user) {
			Log::Info('User not found. Register.');

			$user = new User;
			$user->email = $email;
			$user->user_name = $name;
			$user->role = User::ROLE_USER;
			$user->city = 'Atlanta , GA';
			$user->password = \Hash::make($token);

			$user->save();
		}

		\Auth::login($user, true);

		Log::Info("SessionsController::FacebookCallback. Auth::check(): ". \Auth::check());

		return \Redirect::to('/');
	}

	public function GoogleCallback() {
		Log::Info('SessionsController::GoogleCallback');
	}

}

?>