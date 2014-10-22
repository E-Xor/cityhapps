<?php 

use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

class SessionsController extends BaseController {
	
	public function index() {
		return Response::json(Auth::user());
	}


	public function create() {
		if (Auth::check()) {
			return Redirect::to('/admin');	
		}
		return Redirect::to('/');
	}

	public function login() {

		if (Auth::attempt(array(
			'email' => Input::json('email'), 
			'password' => Input::json('password')
			))) {
			
			// if (Auth::viaRemember()) {
			// 	return Response::json(Auth::user());					
			// } else {
			// 	return Response::json(Auth::user());
			// }
			
		} else {
			return Response::json(array('flash' => 'Invalid username or password'), 500);	
		}		
	}

	public function logout() {
		Auth::logout();
		return Response::json(array('flash' => 'Logged Out!'));
	}

	/**
 * Login user with facebook
 *
 * @return void
 */

	public function fbLogin() {

		    // get data from input
		    $code = Input::get( 'code' );

		    // get fb service
		    $fb = OAuth::consumer( 'Facebook' );

		    // check if code is valid

		    // if code is provided get user data and sign in
		    if ( !empty( $code ) ) {

		        // This was a callback request from facebook, get the token
		        $token = $fb->requestAccessToken( $code );

		        // Send a request with it
		        $result = json_decode( $fb->request( '/me' ), true );

		        $message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		        echo $message. "<br/>";

		        //Var_dump
		        //display whole array().
		        dd($result);

		    }
		    // if not ask for permission first
		    else {
		        // get fb authorization
		        $url = $fb->getAuthorizationUri();

		        // return to facebook login url
		         return Redirect::to( (string)$url );
		    }

		}
	
	}



?>