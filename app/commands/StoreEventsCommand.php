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

		$eventParams['page_size'] = '100';
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
				$activePageCount = Ceil((int)$activeTotalResults / 100);
			}
			if ($activePageCount <= 1) {
				$activeComplete = true;
			}
		} catch (Exception $e) {
			$activeComplete = true;
			$this->error("Active failed at 1. " . $e->getMessage());
		}

		try {
			$eventbritePageCount = Eventbrite::storeEvents($eventParams);
			if ($eventbritePageCount <= 1) {
				$eventbriteComplete = true;
			}
		} catch (Exception $e) {
			$eventbriteComplete = true;
			$this->error("Eventbrite failed at 1. " . $e->getMessage());
		}

		try {
			$eventfulPageCount = Eventful::storeEvents($eventParams);
			if ($eventfulPageCount <= 1) {
				$eventfulComplete = true;
			}
		} catch (Exception $e) {
			$eventfulComplete = true;
			$this->error("Eventful failed at 1. " . $e->getMessage());
		}

		try {
			$meetupTotalResults = Meetup::storeEvents($eventParams);
			if ($meetupTotalResults != null) {
				$meetupPageCount = Ceil((int)$meetupTotalResults / 100);
			}
			if ($meetupPageCount <= 1) {
				$meetupComplete = true;
			}
		} catch (Exception $e) {
			$meetupComplete = true;
			$this->error("Meetup failed at 1. " . $e->getMessage());
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

		$this->info("Start time: " . (string)date('l jS \of F Y h:i:s A'));
		$this->info("Active pages: " . $activePageCount . " Eventbrite pages: " . $eventbritePageCount . " Eventful pages: " . $eventfulPageCount . " Meetup pages: " . $meetupPageCount);
		if (!$activeComplete || !$eventbriteComplete || !$eventfulComplete || !$meetupComplete) {
				
			for ($i = 2; $i <= (int)$loopIndex; $i++) {
				
				$eventParams['page_number'] = $i;

				/* ACTIVE */
				if (!$activeComplete) {
					try {
						$temp = Active::storeEvents($eventParams);

						if ($activePageCount <= $i) {
							$activeComplete = true;
							$this->info("Active completed at " . $i);
						}
					} catch (Exception $e) {
						$this->error("Active FAILED at " . $i . ". " . $e->getMessage());
						$activeComplete = true;
					}
				}

				/* EVENTBRITE */
				if (!$eventbriteComplete) {
					try {
						$temp = Eventbrite::storeEvents($eventParams);

						if ($eventbritePageCount <= $i) {
							$eventbriteComplete = true;
							$this->info("Eventbrite completed at " . $i);
						}
					} catch (Exception $e) {
						$this->error("Eventbrite FAILED at " . $i . ". " . $e->getMessage());
						$eventbriteComplete = true;
					}
				}

				/* EVENTFUL */
				if (!$eventfulComplete) {
					try {
						$temp = Eventful::storeEvents($eventParams);

						if ($eventfulPageCount <= $i) {
							$eventfulComplete = true;
							$this->info("Eventful completed at " . $i);
						}
					} catch (Exception $e) {
						$this->error("Eventful FAILED at " . $i . ". " . $e->getMessage());
						$eventfulComplete = true;
					}
				}

				/* MEETUP */
				if (!$meetupComplete) {
					try {
						$temp = Meetup::storeEvents($eventParams);

						if ($meetupPageCount <= $i) {
							$meetupComplete = true;
							$this->info("Meetup completed at " . $i);
						}
					} catch (Exception $e) {
						$this->error("Meetup FAILED at " . $i . ". " . $e->getMessage());
						$meetupComplete = true;
					}
				}

				$this->info("Completed page loads for: " . (string)$i . " at " . (string)date('l jS \of F Y h:i:s A'));
			}

		}

		$this->info("API loading completed at: " . (string)date('l jS \of F Y h:i:s A'));
		
		try {
			EventRecord::storeEvents();
			$this->info('All events stored in the Events table at: ' . (string)date('l jS \of F Y h:i:s A'));
		} catch (Exception $e) {
			$this->error("EventRecord storeEvents failed to complete. " . $e->getMessage());
		}
	}

}
