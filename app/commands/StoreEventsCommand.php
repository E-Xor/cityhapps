<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StoreEventsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'store-events';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Store events from APIs.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$response = '';

		/* ACTIVE */
		$eventParams = array();

		$eventParams['page_size'] = '50';
		$eventParams['page_number'] = '1';

		$loopIndex = 0;

		$activeComplete = false;
		$eventbriteComplete = false;
		$eventfulComplete = false;
		$meetupComplete = false;

		$activeTotalResults = 0;
		$activePageCount = 0;
		$eventbriteTotalResults = 0;
		$eventbritePageCount = 0;
		$eventfulTotalResults = 0;
		$eventfulPageCount = 0;
		$meetupTotalResults = 0;
		$meetupPageCount = 0;

		try {
			$activeTotalResults = Active::storeEvents($eventParams);
			if ($activeTotalResults != null) {
				$activePageCount = Ceil((int)$activeTotalResults / 50);
			}
			if ($activePageCount <= 1) {
				$activeComplete = true;
			}
		} catch (Exception $e) {
			$activeComplete = true;
			$response .= "Active failed at 1<br />";
		}

		try {
			$eventbritePageCount = Eventbrite::storeEvents($eventParams);
			if ($eventbritePageCount <= 1) {
				$eventbriteComplete = true;
			}
		} catch (Exception $e) {
			$eventbriteComplete = true;
			$response .= "Eventbrite failed at 1<br />";
		}

		try {
			$eventfulPageCount = Eventful::storeEvents($eventParams);
			if ($eventfulPageCount <= 1) {
				$eventfulComplete = true;
			}
		} catch (Exception $e) {
			$eventfulComplete = true;
			$response .= "Eventful failed at 1<br />";
		}

		try {
			$meetupTotalResults = Meetup::storeEvents($eventParams);
			if ($meetupTotalResults != null) {
				$meetupPageCount = Ceil((int)$meetupTotalResults / 50);
			}
			if ($meetupPageCount <= 1) {
				$meetupComplete = true;
			}
		} catch (Exception $e) {
			$meetupComplete = true;
			$response .= "Meetup failed at 1<br />";
		}

		$loopIndex = $activePageCount;
		if ($eventbritePageCount > $loopIndex) {
			$loopIndex = $eventbritePageCount;
		}
		if ($eventfulPageCount > $loopIndex) {
			$loopIndex = $eventfulPageCount;
		}
		if ($meetupPageCount > $loopIndex) {
			$loopIndex = $meetupPageCount;
		}

		$response .= "A: " . $activePageCount . " EB: " . $eventbritePageCount . " EF: " . $eventfulPageCount . " M: " . $meetupPageCount . "<br />";
		if (!$activeComplete || !$eventbriteComplete || !$eventfulComplete || !$meetupComplete) {
				
			for ($i = 2; $i <= (int)$loopIndex; $i++) {
				
				$eventParams['page_number'] = $i;

				/* ACTIVE */
				if (!$activeComplete) {
					try {
						$temp = Active::storeEvents($eventParams);

						$response .= "Active " . $i . "<br />";

						if ($activePageCount <= $i) {
							$activeComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Active failed at " . $i . "<br />";
						$activeComplete = true;
					}
				}

				/* EVENTBRITE */
				if (!$eventbriteComplete) {
					try {
						$temp = Eventbrite::storeEvents($eventParams);

						$response .= "Eventbrite " . $i . "<br />";

						if ($eventbritePageCount <= $i) {
							$eventbriteComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Eventbrite failed at " . $i . "<br />";
						$eventbriteComplete = true;
					}
				}

				/* EVENTFUL */
				if (!$eventfulComplete) {
					try {
						$temp = Eventful::storeEvents($eventParams);

						$response .= "Eventful " . $i . "<br />";

						if ($eventfulPageCount <= $i) {
							$eventfulComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Eventful failed at " . $i . "<br />";
						$eventfulComplete = true;
					}
				}

				/* MEETUP */
				if (!$meetupComplete) {
					try {
						$temp = Meetup::storeEvents($eventParams);

						$response .= "Meetup " . $i . "<br />";

						if ($meetupPageCount <= $i) {
							$meetupComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Meetup failed at " . $i . "<br />";
						$meetupComplete = true;
					}
				}
			}

		}
		

		//EventRecord::storeEvents();
		//$this->info('All events stored in the Events table.');
	}

}
