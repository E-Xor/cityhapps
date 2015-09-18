<?php

namespace CityHapps\Http\Controllers;

use CityHapps;
use DB;
use Auth;
use CityHapps\Favorite;
use CityHapps\User;
use Illuminate\Http\Request;
use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
class FavoriteController extends Controller {

  public function getFavorites($id) {
      $results = DB::table('favorites')
              ->join('happs', 'favorites.event_id', '=', 'happs.id')
              ->where('favorites.user_id', '=', $id)
              ->select('happs.*')
              ->get();
      return $results;
  }
  
  public function check(Request $request) {
      $id = $request->input('id');
      $user_id = $request->input('user_id');

      $results = DB::table('favorites')
               ->where('event_id', '=', $id)
               ->where('user_id', '=', $user_id)
               ->count();
      return $results;
  }

  public function add(Request $request) {
      
      $id = $request->input('id');
      $user_id = $request->input('user_id');

      $check = DB::table('favorites')
               ->where('event_id', '=', $id)
               ->where('user_id', '=', $user_id)
               ->count();
      if($check > 0) 
      { 
          DB::table('favorites')
          ->where('event_id', '=', $id)
          ->where('user_id', '=', $user_id)
          ->delete();
          return $id;
      }

      error_log($id);
      error_log($user_id);
      DB::table('favorites')->insert(
          ['user_id' => $user_id, 'event_id' => $id]
      );
      return $id;
  }


}
