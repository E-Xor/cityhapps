<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ConvertEventsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'convert-events';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Convert events from each local table to main events table.';

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
		$this->info('Started Convert Events at ' . (string)date('l jS \of F Y h:i:s A'));

		try {
			EventRecord::storeEventfulEvents();
			$this->info("Eventful events converted at "  . (string)date('l jS \of F Y h:i:s A'));
		} catch (Exception $e) {
			$this->error("Eventful convert events FAILED to complete. " . $e->getMessage());
		}

		try {
			EventRecord::storeMeetupEvents();
			$this->info("Meetup events converted at "  . (string)date('l jS \of F Y h:i:s A'));
		} catch (Exception $e) {
			$this->error("Meetup convert events FAILED to complete. " . $e->getMessage());
		}

		try {
			EventRecord::storeActiveEvents();
			$this->info("Active events converted at "  . (string)date('l jS \of F Y h:i:s A'));
		} catch (Exception $e) {
			$this->error("Active convert events FAILED to complete. " . $e->getMessage());
		}

		try {
			EventRecord::storeEventbriteEvents();
			$this->info("Eventbrite events converted at "  . (string)date('l jS \of F Y h:i:s A'));
		} catch (Exception $e) {
			$this->error("Eventbrite convert events FAILED to complete. " . $e->getMessage());
		}

		//EventRecord::storeEvents();
		
		$this->info('All events stored in the Events table at: ' . (string)date('l jS \of F Y h:i:s A'));
		
	}

}
