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
		Active::storeEvents();
		$this->info('Active events stored.');
		Eventbrite::storeEvents();
		$this->info('Eventbrite events stored.');
		Eventful::storeEvents();
		$this->info('Eventful events stored.');
		Meetup::storeEvents();
		$this->info('Meetup events stored.');
		EventRecord::storeEvents();
		$this->info('All events stored in the Events table.');
	}

}
