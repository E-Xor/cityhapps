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
		//Active::storeEvents();
		//$this->info('Active events stored.');
		//Eventbrite::storeEvents();
		//$this->info('Eventbrite events stored.');
		//Meetup::storeEvents();
		//$this->info('Meetup events stored.');
		
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
				}
			}
		}

		$this->info('Eventful events stored.');
		

		EventRecord::storeEvents();
		$this->info('All events stored in the Events table.');
	}

}
