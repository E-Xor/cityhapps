<?php

namespace CityHapps\Http\Controllers;

use CityHapps\Venue;
use CityHapps\User;
use Input;

class SimilarVenueController extends Controller {
    public function __construct() {
        $this->user = $this->authFromToken();
    }


    public function index($id)
    {
        $venue = Venue::find($id);
        if (!$this->authorizeResource($venue)) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Unauthorized'], 403);
        }

        return $venue->similar;
    }

    protected function authFromToken() {
        $user = parent::authFromToken();
        if ($user->role != User::ROLE_USER) {
            return $user;
        }
    }
}
