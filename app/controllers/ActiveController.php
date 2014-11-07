<?php 


class ActiveController extends BaseController { 

	public function events() {

		// Active.com API Search v2 (http://api.amp.active.com/v2/search)
		// API Key = 'f4kzrwzqywvtcyntepb9zt5f';

		$event = file_get_contents('http://api.amp.active.com/v2/search/?near=Atlanta%2CGA%2CUS&current_page=1&per_page=100&sort=distance&exclude_children=true&api_key=f4kzrwzqywvtcyntepb9zt5f');

		return $event;
	}

	public function storeEvents() {
		Active::storeEvents();
	}

}

?>