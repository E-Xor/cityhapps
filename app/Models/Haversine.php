<?php
    /*
     *  Calculate the distance between 2 points, in Laravel.
     *  @package Haversine
     *  @subpackage Models
     *  @version 0.0.1
     *  @author Douglas Grubba
     *  @access public
     *  @copyright 2012 Douglas Grubba
     *  @link http://douggdev.me
	*/

    use Carbon\Carbon;

    class Haversine extends Eloquent {
        /**
         * name of the table
         *
         * @access  public
         * @var     string
         */
        protected  $table = 'happs';
        
        public function __construct()
        {
            parent::__construct();
        }
        /*
         *  find the n closest locations
         *  @param float $lat latitude of the point of interest
         *  @param float $lng longitude of the point of interest
         *  @return array
         */
        public static function closestCoords( $lat, $lng, $max_distance = 10, $max_locations = 10, $units = 'miles', $fields = false )
        {
            /*
             *  Allow for changing of units of measurement
             */
            switch ( $units ) {
                case 'miles':
                    //radius of the great circle in miles
                    $gr_circle_radius = 3959;
                break;
                case 'kilometers':
                    //radius of the great circle in kilometers
                    $gr_circle_radius = 6371;
                break;
            }
            /*
             *  Support the selection of certain fields
             */
            if( ! $fields ) {
                $fields =  array('id', 'url', 'source_id', 'latitude', 'longitude', 'event_name', 'venue_name', 'address', 
                                'description', 'start_time', 'event_image_url', 
                                );
            }
            /*
             *  Generate the select field for disctance
             */
                   $distance_select = sprintf(           
                        "ROUND(( %d * acos( cos( radians(%s) ) " .
                                " * cos( radians( latitude ) ) " .
                                " * cos( radians( longitude ) - radians(%s) ) " .
                                " + sin( radians(%s) ) * sin( radians( latitude ) ) " .
                            " ) " . 
                        ")
                        , 2 ) " . 
                        "AS distance
                        ",
                        $gr_circle_radius,               
                        $lat,
                        $lng,
                        $lat
                       );

            $data = DB::table('happs')
                ->select( DB::raw( implode( ',' ,  $fields ) . ',' .  $distance_select  ) )
                ->where('start_time', ">=", Carbon::now())
                ->having( 'distance', '<=', $max_distance )
                ->orderBy( 'distance', 'asc' )
                ->orderBy('start_time', 'asc')
                ->orderBy('user_id', 'desc')
                ->take(10)
                ->get();

            return $data;


            // return DB::table('events')
            //     ->having( 'distance', '<', 20 )
            //     ->take( $max_locations )
            //     ->orderBy( 'distance', 'asc' )
            //     ->get( array($fields, $distance_select) );                
        
            // dd(DB::getQueryLog());    

        }
    }
