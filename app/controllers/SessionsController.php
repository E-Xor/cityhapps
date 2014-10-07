<?php 

use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

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

	public function fbNewLogin() {



		/**
		 * Bootstrap the example
		 */
		// require_once __DIR__ . '/bootstrap.php';

		// Session storage
		$servicesCredentials = array(
			'facebook' => array(
	            'key'     => '895139070496415',
	            'secret' => '72282e6b1707504649382bd8607312c5',
	            'scope'         => array('read_friendslist')
	        )
        );		


		$storage = new Session();

		// Setup the credentials for the requests
		$credentials = new Credentials(
		    $servicesCredentials['facebook']['key'],
		    $servicesCredentials['facebook']['secret'],
		    $currentUri->getAbsoluteUri()
		);

		// Instantiate the Facebook service using the credentials, http client and storage mechanism for the token
		/** @var $facebookService Facebook */
		$facebookService = $serviceFactory->createService('facebook', $credentials, $storage, array());

		if (!empty($_GET['code'])) {
		    // This was a callback request from facebook, get the token
		    $token = $facebookService->requestAccessToken($_GET['code']);

		    // Send a request with it
		    $result = json_decode($facebookService->request('/me'), true);

		    // Show some of the resultant data
		    echo 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];

		} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
		    $url = $facebookService->getAuthorizationUri();
		    header('Location: ' . $url);
		} else {
		    $url = $currentUri->getRelativeUri() . '?go=go';
		    echo "<a href='$url'>Login with Facebook!</a>";
		}
	}

		
	}



?>