<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StaleApiData extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'api:clear-stale';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clears stale API data from main events table.';

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
    // output some log info
    $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
    $this->line("Truncating all events that are not in API Tables (Eventbrite, Eventful, Meetup) and events that are in the past");

    // This finds the url's that are in events but not in any of the other tables! then deletes them
    $query = EventRecord::clearStaleApi();
    if ($query == -1)
      $this->info("Looks like the API Tables are clear! (No stale data to check against)");
    else
      $this->info("Deleted " . $query . " row(s) from events table");
  }
}
