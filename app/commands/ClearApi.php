<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ClearApi extends Command
{
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
        $select = $this->option('select');

        //confirmation, return if no
        if (!$this->confirm("Are you sure you want to clear ". strtoupper($select) ." API Tables? [yes|no]", true)) {
            return;
        }
        $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
        switch ($select) {
            case 'events':
                $this->removeEvents();
                break;
            case 'venues':
                $this->removeVenues();
                break;
            case 'all':
                $this->info('Removing ALL(venues and events) from API Tables (Foursquare, Eventful, Meetup, Eventbrite)');
                $this->removeEvents();
                $this->line('Marked time: ' . (string)date('l jS \of F Y h:i:s A'));
                $this->removeVenues();
                break;
            default:
                $this->error(
                    'API Tables "' . $select . '" not recognized. Must be one of the following: events, venues, all'
                );
                break;
        }
        $this->line('End time: ' . (string)date('l jS \of F Y h:i:s A'));
    }

    private function removeEvents()
    {
        // output some log info
        $this->line("Removing all events from API Tables (Eventbrite, Eventful, Meetup)");
        //delete eventbrite
        Eventbrite::truncate();
        $this->info("Eventbrite events - Done");

        //delete eventful
        Eventful::truncate();
        $this->info("Eventful events - Done");

        // delete meetup
        Meetup::truncate();
        $this->info("Meetup events - Done");
    }

    private function removeVenues()
    {
        // output some log info

        $this->line("Removing all venues from API Tables (Foursquare, Eventful, Meetup)");

        //delete foursquare
        FoursquareVenues::truncate();
        $this->info("Foursquare venues - Done");

        //delete eventful
        EventfulVenues::truncate();
        $this->info("Eventful venues - Done");

        // delete meetup
        MeetupVenues::truncate();
        $this->info("Meetup venues - Done");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            array('select', 's', InputOption::VALUE_OPTIONAL, 'Choose events, venues or all APIs Tables you want to clear', 'all'),
        );
    }
}
