<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PullApi extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'api:pull';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Populate API tables from their respective apis.';

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
    if ($this->option('eventbrite'))
      $this->grabEventbrite();
    elseif ($this->option('eventful'))
      $this->grabEventful();
    elseif ($this->option('meetup'))
      $this->grabMeetup();
    elseif ($this->option('all'))
    {
      $this->grabEventbrite();
      $this->grabEventful();
      $this->grabMeetup();
    }
    else
    {
      $this->info("Usage:");
      $this->comment("\t api:pull [--eventbrite] [--eventful] [--meetup] [-a|--all]");
    }
  }
  /**
   * Use the Eventbrite API to populate api tables
   *
   */
  public function grabEventbrite()
  {
      $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
      $this->comment("Started Eventbrite");
      $eb = new EventbriteController;
      $eb->storeEvents();
      $this->info("Finished Eventbrite");
  }
  /**
   * Use the Eventful API to populate api tables
   *
   */
  public function grabEventful()
  {
      $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
      $this->comment("Started Eventful");
      $eb = new EventfulController;
      $eb->storeEvents();
      $this->info("Finished Eventful");
  }
  /**
   * Use the meetup API to populate api tables
   *
   */
  public function grabMeetup()
  {
      $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
      $this->comment("Started Meetup");
      $eb = new MeetupController;
      $eb->storeEvents();
      $this->info("Finished Meetup");
  }
  /**
   * Get the console command options.
   *
   * @return array
   */
  public function getOptions()
  {
    return array(
      array('eventbrite', null, InputOption::VALUE_NONE, 'Grab Eventbrite API', null),
      array('eventful', null, InputOption::VALUE_NONE, 'Grab Eventful API', null),
      array('meetup', null, InputOption::VALUE_NONE, 'Grab Meetup API', null),
      array('all', 'a', InputOption::VALUE_NONE, 'Grab All API\'s (slow)', null)
    );
  }

}
