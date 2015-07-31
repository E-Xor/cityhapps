<?php

namespace CityHapps\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;

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
  protected $description = 'Populate API tables from their respective APIs.';

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
    $this->line('Start time: ' . (string)date('l jS \of F Y h:i:s A'));
    $select = $this->option('select');
    switch ($select) {
      case 'eventbrite':
        $this->grabEventbrite();
        break;
      case 'eventful':
        $this->grabEventful();
        break;
      case 'meetup':
        $this->grabMeetup();
        break;
      case 'all':
        $this->info('Running all integrations: eventbrite, eventful, meetup');
        $this->grabEventbrite();
        $this->line('Marked time: ' . (string)date('l jS \of F Y h:i:s A'));
        $this->grabEventful();
        $this->line('Marked time: ' . (string)date('l jS \of F Y h:i:s A'));
        $this->grabMeetup();
        break;
      default:
        $this->error('Integration "' . $select . '" not recognized. Must be one of the following: eventbrite, eventful, meetup, all');
        break;
    }
    $this->line('End time: ' . (string)date('l jS \of F Y h:i:s A'));    
  }

  /**
   * Use the Eventbrite API to populate api tables
   *
   */
  public function grabEventbrite()
  {
    $this->comment('Started Eventbrite');
    $this->info('Getting first page; waiting for page count...');

    $modelObject = new \Eventbrite;
    $eventParams = array(
      'page_size' => '',
      'page_number' => '1',
    );
    $pageCount = $modelObject->storeEvents($eventParams);

    if (isset($pageCount) && (int)$pageCount > 1) {
      $progress = new ProgressBar($this->output, (int)$pageCount);
      $progress->setFormat('very_verbose');
      $progress->start();
      $progress->advance();
      // We'll go through the pages backwards so we don't have to think about pages
      // being added/removed while working through the process
      for ($i = (int)$pageCount; $i >= 2; $i--) {
        $eventParams['page_number'] = $i;
        $modelObject->storeEvents($eventParams);
        $progress->advance();
      }
      $progress->finish();
    }

    $this->line(''); // New line after the progress bar
    $this->comment('Finished Eventbrite');
  }

  /**
   * Use the Eventful API to populate API tables
   */
  public function grabEventful()
  {
    $this->comment('Started Eventful');
    $this->info('Getting first page; waiting for page count...');

    $modelObject = new \Eventful;
    $eventParams = array(
      'page_size' => '',
      'page_number' => '1',
    );
    $pageCount = $modelObject->storeEvents($eventParams);

    if (isset($pageCount) && (int)$pageCount > 1) {
      $progress = new ProgressBar($this->output, (int)$pageCount);
      $progress->setFormat('very_verbose');
      $progress->start();
      $progress->advance();
      // We'll go through the pages backwards so we don't have to think about pages
      // being added/removed while working through the process
      for ($i = (int)$pageCount; $i >= 2; $i--) {
        $eventParams['page_number'] = $i;
        $modelObject->storeEvents($eventParams);
        $progress->advance();
      }
      $progress->finish();
    }

    $this->line(''); // New line after the progress bar
    $this->comment('Finished Eventful');
  }

  /**
   * Use the Meetup API to populate api tables
   */
  public function grabMeetup() {
    $this->comment('Started Meetup');
    $this->info('Getting first page; waiting for page count...');

    $modelObject = new \Meetup;
    $eventParams = array(
      'page_size' => '',
      'page_number' => '1',
    );
    $pageCount = $modelObject->storeEvents($eventParams);

    if (isset($pageCount) && (int)$pageCount > 1) {
      $progress = new ProgressBar($this->output, (int)$pageCount);
      $progress->setFormat('very_verbose');
      $progress->start();
      $progress->advance();
      // We'll go through the pages backwards so we don't have to think about pages
      // being added/removed while working through the process
      for ($i = (int)$pageCount; $i >= 2; $i--) {
        $eventParams['page_number'] = $i;
        $modelObject->storeEvents($eventParams);
        $progress->advance();
      }
      $progress->finish();
    }

    $this->line(''); // New line after the progress bar
    $this->comment('Finished Meetup');
  }

  /**
   * Generic call to populate API tables
   */
  public function grabEventIntigration($name)
  {
    // TODO: get integrations listing to grab label and class name
    $label = $name;
    $class = ucfirst($name);

    $this->comment('Started ' . $name);
    $this->info('Getting first page; waiting for page count...');

    $modelObject = new $class;
    $eventParams = array(
      'page_size' => '',
      'page_number' => '1',
    );
    $pageCount = $modelObject->storeEvents($eventParams);

    if (isset($pageCount) && (int)$pageCount > 1) {
      $progress = new ProgressBar($this->output, (int)$pageCount);
      $progress->setFormat('very_verbose');
      $progress->start();
      $progress->advance();
      // We'll go through the pages backwards so we don't have to think about pages
      // being added/removed while working through the process
      for ($i = (int)$pageCount; $i >= 2; $i--) {
        $eventParams['page_number'] = $i;
        $modelObject->storeEvents($eventParams);
        $progress->advance();
      }
      $progress->finish();
    }

    $this->line(''); // New line after the progress bar
    $this->comment('Finished ' . $name);
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  public function getOptions()
  {
    return array(
      array('select', 's', InputOption::VALUE_OPTIONAL, 'Choose an Integration to Pull', 'all'),
    );
  }

}
