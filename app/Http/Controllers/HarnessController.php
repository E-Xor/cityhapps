<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;

class HarnessController extends Controller {

	public function showHarness()
	{
		return View::make('harness');
	}

}
