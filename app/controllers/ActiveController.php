<?php 


class ActiveController extends BaseController { 

	public function events() {

		// Active.com API Search v2 (http://api.amp.active.com/v2/search)
		// API Key = 'f4kzrwzqywvtcyntepb9zt5f';

		$event = file_get_contents('http://api.amp.active.com/v2/search/?near=Atlanta%2CGA%2CUS&current_page=1&per_page=100&sort=distance&exclude_children=true&api_key=f4kzrwzqywvtcyntepb9zt5f');

		return $event;
	}

	public function storeEvents() {
		
		$response = '';

		$eventParams = array();

		$eventParams['page_size'] = '50';
		$eventParams['page_number'] = '1';

		$totalResults = Active::storeEvents($eventParams);
		$pageCount = null;

		if ($totalResults != null) {
			$pageCount = Ceil((int)$totalResults / 50);
		}

		if ($pageCount != null) {
			if ((int)$pageCount > 1) {
				for ($i = 2; $i <= (int)$pageCount; $i++) {
					$eventParams['page_number'] = $i;
					try {
						$newTotalResults = Active::storeEvents($eventParams);

						$response .= $i . "<br />";
					} catch (Exception $e) {
						$response .= 'Skipped ' . $i . "<br />";
					}
				}
			}
		}

		echo $response;

	}

}

?>