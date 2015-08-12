<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use CityHapps\User;
use Illuminate\Http\Request;

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
	public function store(Request $request)
	{

		$returnUser = '';

		//Dont Send email if Facebook user
        $request->request->get('email');

		Mail::send('emails.welcome', array('key' => 'value'), function($message) use ($request){

			$message->from('team@cityhapps.com', 'CityHapps');

			$email = $request->request->get('email');
			
			$message->to($email, $email)->subject('Welcome to CityHapps!');

		});

		$json = $request->request->all();

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

		//Checkboxes on client are fussy and need a flat 1D object

		$id = Input::only('id');

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


	public function check(Request $request)
	{
		//Add Laravel email validation check
		$email = $request->request->get('value');
		$rules = array('email' => 'unique:users,email');
		
		$userID = User::where('email', '=', $email)->pluck('id');

		$validator = Validator::make(array('email' => $email), $rules);

		if ($validator->fails()) {
			
			echo json_encode(array('isValid' => false,
									'id' => $userID ));
			return;

		} else {
			echo json_encode(array('isValid' => true, 
									'value' => 'nice ' . $email));
			return;
		}

	}

    public function exist(Request $request)
    {
        //Add Laravel email validation check

        $email = $request->request->get('value');
        $rules = array('email' => 'unique:users,email');

        $userID = User::where('email', $email)->pluck('id');

        $validator = Validator::make(array('email' => $email), $rules);

        if ($validator->fails()) {

            echo json_encode(array('isValid' => true,
                'value' => 'nice'));
            return;

        } else {

            echo json_encode(array('isValid' => false,
                'id' => $userID ));
            return;

        }

    }

    public function resetPassword() {

        $email = Input::only('email')['email'];

        $user = User::where('email', $email)->first();

        $password = substr(sha1($email . time()), 0, 12);

        if(!is_null($user)) {

            $user->password = Hash::make($password);

            $user->save();

            Mail::send('emails.reset', array('password' => $password), function($message) use ($email){

                $message->from('team@cityhapps.com', 'CityHapps');

                $message->to($email, $email)->subject('Reset Password on CityHapps!');

            });

            return json_encode(array('status' => 'ok', 'message' => 'Successfully'));
        }

        return json_encode(array('status' => 'error', 'message' => 'Have errors'));


    }

    public function getUserData()
    {
        $user = User::find((int) Auth::user()->id);

        $userName = $user->user_name;
        $userEmail = $user->email;

        return json_encode(['username' => $userName, 'email' => $userEmail]);

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
     * Update user data
     *
     * @return string
     */
    public function editUser()
    {
        $formData = Input::only('email', 'password', 'username');

        $email = trim($formData['email']);
        $password = trim($formData['password']);
        $username = trim($formData['username']);

        $userId = DB::table('users')->where('email', $email)->pluck('id');
        $user = User::find((int) $userId);

            try {
                if (!is_null($user)) {

                    $user->password = Hash::make($password);
                    $user->user_name = $username;

                    $user->save();

                    return json_encode(array('status' => 'ok', 'message' => 'Successfully'));
                }

                return json_encode(array('status' => 'error', 'message' => 'some errors'));

            } catch (Exception $e) {

                return json_encode(array('status' => 'error', 'message' => $e->getMessage()));
            }

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
