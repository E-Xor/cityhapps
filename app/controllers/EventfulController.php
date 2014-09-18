<?php 

require __DIR__ . '/../services/Eventful.php';

class EventfulController extends BaseController { 

	public function events() {

		// Enter your application key here. (See http://api.eventful.com/keys/)
			$app_key = 'pLB3SGLn9xSnfcg5';

			$ev = new Services_Eventful($app_key);

			print_r($ev);

			// All method calls other than login() go through call().
			$args = array(
			  'id' => $_REQUEST['id'],
			);

			$event = $ev->call('events/get', $args);

			if ( PEAR::isError($event) )
			{
			    print("An error occurred: " . $event->getMessage() . "\n");
			    print_r( $ev );
			}

			// The return value from a call is a SimpleXMLElement object.


			print_r( $event );  


		}

	}
	



?>