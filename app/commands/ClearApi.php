<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearApi extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'api:clear';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clear API Tables';

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
    //confirmation, return if no
    if (!$this->confirm("Are you sure you want to clear API Tables? [yes|no]", true))
      return;

    // output some log info
    $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
    $this->line("Truncating all API Tables (Eventbrite, Eventful, Meetup)");

    //delete eventbrite
    Eventbrite::truncate();
    $this->info("Eventbrite - Done");

    //delete eventful
    Eventful::truncate();
    $this->info("Eventful - Done");

    // delete meetup
    Meetup::truncate();
    $this->info("Meetup - Done");
  }
}
