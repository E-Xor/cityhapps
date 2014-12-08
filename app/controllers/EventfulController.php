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
		$response = '';

		$eventParams = array();

		$eventParams['page_size'] = '100';
		$eventParams['page_number'] = '1';

		$pageCount = Eventful::storeEvents($eventParams);
		$pageCount = 10;

		if ($pageCount != null) {
			if ((int)$pageCount > 1) {
				for ($i = 2; $i <= (int)$pageCount; $i++) {
					$eventParams['page_number'] = $i;
					$newPageCount = Eventful::storeEvents($eventParams);

					if ($newPageCount != null) {
						if ((int)$newPageCount != (int)$pageCount) {
							//$pageCount = $newPageCount;
						}
					}

					$response .= $i . "<br />";
				}
			}
		}

		echo $response;
	}

}

?>