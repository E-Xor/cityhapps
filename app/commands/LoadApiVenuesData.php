<?php

use Illuminate\Console\Command;

class LoadApiVenuesData extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'api:load-venues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Combines all API venues tables into the main venues table';

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
        $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
        $this->line("Loading all API Venues Tables (Foursquare, Eventful, Meetup, Eventbrite) into main venues table...");
        VenueController::storeVenues();
        $this->info("Finished storing venues");
    }
}
