<?php 


class EventfulController extends BaseController { 

	public function events() {

		// Enter your application key here. (See http://api.eventful.com/keys/)
		// $app_key = 'pLB3SGLn9xSnfcg5';

		$event = file_get_contents('http://api.eventful.com/json/events/search?location=Atlanta&app_key=pLB3SGLn9xSnfcg5');
			// &date=today&image_sizes=block250&sort_order=date&sort_direction=descending&app_key=pLB3SGLn9xSnfcg5');

		return $event;
	}

	public function storeEvents() {
		Eventful::storeEvents();
	}

}

?>