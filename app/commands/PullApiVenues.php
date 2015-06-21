<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;

class PullApiVenues extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'api:pull-venues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Venues API tables from their respective APIs.';

    /**
     * Create a new command instance.
     *
     * void function
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
            case 'foursquare':
                $this->grabFoursquare();
                break;
            case 'eventful':
                $this->grabEventful();
                break;
            case 'meetup':
                $this->grabMeetup();
                break;
            case 'all':
                $this->info('Running all venues integrations: foursquare, eventful, meetup');
                $this->grabFoursquare();
                $this->line('Marked time: ' . (string)date('l jS \of F Y h:i:s A'));
                $this->grabEventful();
                $this->line('Marked time: ' . (string)date('l jS \of F Y h:i:s A'));
                $this->grabMeetup();
                break;
            default:
                $this->error(
                    'Integration "' . $select . '" not recognized. Must be one of the following: foursquare, eventful, meetup, all'
                );
                break;
        }
        $this->line('End time: ' . (string)date('l jS \of F Y h:i:s A'));
    }

    /**
     * Use the Meetup API to populate api tables
     */
    public function grabMeetup()
    {
        $this->comment('Started Meetup');
        $this->info('Getting first page; waiting for page count...');

        $modelObject = new MeetupVenues;
        $venueParams = array(
            'page_size' => '',
            'page_number' => '1',
        );
        $pageCount = $modelObject->storeVenues($venueParams);

        if (isset($pageCount) && (int)$pageCount > 1) {
            $progress = new ProgressBar($this->output, (int)$pageCount);
            $progress->setFormat('very_verbose');
            $progress->start();
            $progress->advance();

            for ($i = (int)$pageCount; $i >= 2; $i--) {
                $venueParams['page_number'] = $i;
                $modelObject->storeVenues($venueParams);
                $progress->advance();
            }
            $progress->finish();
        }

        $this->line(''); // New line after the progress bar
        $this->comment('Finished Meetup');
    }

    /**
     * Use the Meetup API to populate api tables
     */
    public function grabEventful()
    {
        $this->comment('Started Eventful');
        $this->info('Getting first page; waiting for page count...');

        $modelObject = new EventfulVenues;
        $venueParams = array(
            'page_size' => '',
            'page_number' => '1',
        );
        $pageCount = $modelObject->storeVenues($venueParams);

        if (isset($pageCount) && (int)$pageCount > 1) {
            $progress = new ProgressBar($this->output, (int)$pageCount);
            $progress->setFormat('very_verbose');
            $progress->start();
            $progress->advance();

            for ($i = (int)$pageCount; $i >= 2; $i--) {
                $venueParams['page_number'] = $i;
                $modelObject->storeVenues($venueParams);
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
    public function grabFoursquare()
    {
        $this->comment('Started Foursquare');
        $this->info('Getting first page; waiting for page count...');

        $modelObject = new FoursquareVenues();
        $venueParams = array(
            'page_size' => '',
            'page_number' => '1',
        );
        $pageCount = $modelObject->storeVenues($venueParams);

        if (isset($pageCount) && (int)$pageCount > 1) {
            $progress = new ProgressBar($this->output, (int)$pageCount);
            $progress->setFormat('very_verbose');
            $progress->start();
            $progress->advance();

            for ($i = (int)$pageCount; $i >= 2; $i--) {
                $venueParams['page_number'] = $i;
                $modelObject->storeVenues($venueParams);
                $progress->advance();
            }
            $progress->finish();
        }

        $this->line(''); // New line after the progress bar
        $this->comment('Finished Foursquare');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            array('select', 's', InputOption::VALUE_OPTIONAL, 'Choose a Venues Integration to Pull', 'all'),
        );
    }

}
