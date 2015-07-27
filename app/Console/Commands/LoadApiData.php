<?php

namespace CityHapps\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LoadApiData extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'api:load';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Combines all API tables into the main table';

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
    //

    $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
    $this->line("Loading all API Tables (Eventbrite, Eventful, Meetup) into main table...");

    $eb = new \EventController;
    $eb->storeEvents();
    $this->info("Finished storing events");
  }
}
