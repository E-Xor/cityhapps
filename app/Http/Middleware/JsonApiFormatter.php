<?php

namespace CityHapps\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;

class JsonApiFormatter {

    public static function getJsonContentType()
    {
        return 'application/vnd.api+json';
    }

    /**
     * @return DateTimeZone $object
     */
    private static function getSiteTimeZoneObj()
    {
        return new \DateTimeZone(self::getSiteTimeZone()) ;
    }

    private static function getUtcTimeZoneObj()
    {
        return new \DateTimeZone("UTC");
    }

    /**
     * @return string timezone America/New_York
     */
    private static function getSiteTimeZone()
    {
        return 'America/New_York';
    }

    /**
     * @param null $time
     *
     * @return array
     */
    private static function getHappTimeStructure($time = null)
    {
        $happTime = [];
        if(!is_null($time)) {

            $currentTime = new \DateTime($time, self::getSiteTimeZoneObj());
            $localTime = $currentTime->format(\DateTime::ISO8601);
            $currentTime->setTimezone(self::getUtcTimeZoneObj());
            $utcTime = $currentTime->format(\DateTime::ISO8601);

            $happTime  = [
                'timezone' => self::getSiteTimeZone(),
                'local' => $localTime,
                'utc' => $utcTime,
            ];
        }

        return $happTime;
    }

    /**
     * @param $startTime
     * @param $endTime
     *
     * @return array $happTime
     */
    private static function getHappTimeData($startTime, $endTime)
    {
        $happTime = [
            'start' => null,
            'end' => null
        ];

        if(!is_null($startTime)) {
            $happTime['start'] = self::getHappTimeStructure($startTime);
        }
        if(!is_null($endTime)) {
            $happTime['end'] = self::getHappTimeStructure($endTime);
        }

        return $happTime;
    }

    private static function jsonApiHappStructure($happs = array(), $statusCode = Response::HTTP_NOT_FOUND, $errors = array('404' => 'Content not found'))
    {

        $selfUrl = url('/happs');

        $result = [];
        $data = [];
        if(count($happs) > 0) {

            foreach($happs as $key => $happ) {

                /**
                 * formatted happ categories
                 */
                $categories = [];
                if(count($happ->categories) > 0) {
                    foreach($happ->categories as $category) {
                        $categories['data'][] = [
                            'type' => 'categories',
                            'id' => $category->id,
                        ];
                    }
                }

                /**
                 * formatted happ tags
                 */
                $tags = [];
                if(count($happ->tags) > 0) {
                    foreach($happ->tags as $tag) {
                        $tags['data'][] = [
                            'type' => 'tags',
                            'id' => $tag->id,
                        ];
                    }
                }

                /**
                 * formatted happ venue
                 */
                $venue = [];
                if(isset($happ->venue_id) || isset($happ->venue_name) || isset($happ->venue_url)) {
                    $venue['data']['type'] = 'venues';
                    $venue['data']['id'] = $happ->venue_id;
                }

                /**
                 * formatted happ start and end time
                 */
                $happTime = self::getHappTimeData($happ->start_time, $happ->end_time);

                /**
                 * formatted all happ data
                 */
                $data[$key]['type'] = 'happs';
                $data[$key]['id'] = $happ->id;

                if(count($happs) > 1) {
                    $data[$key]['links'] = [
                        'related' => $selfUrl . '/' . $happ->id,
                    ];
                }

                $data[$key]['attributes'] = [
                    'event_name' => $happ->event_name,
                    'url' => $happ->url,
                    'source_id' => $happ->source_id,
                    'status' => $happ->status,
                    'start' => $happTime['start'],
                    'end' => $happTime['end'],
                    'address' => [
                        'street_1' => $happ->address,
                        'city' => $happ->city,
                        'state' => $happ->state,
                        'zip' => $happ->zip,
                    ],
                    'description' => $happ->description,
                    'all_day_flag' => $happ->all_day_flag,
                    'event_image_url' => $happ->event_image_url,
                    'geo_data' => [
                        'latitude' => $happ->latitude,
                        'longitude' => $happ->longitude,
                        'google_directions_link' => $happ->google_directions_link,
                        'google_map_large' => $happ->google_map_large,
                    ]
                ];

                $data[$key]['relationships'] = [];
                if(count($categories) > 0) {
                    $data[$key]['relationships']['categories'] = $categories;
                    $data[$key]['relationships']['categories']['links'] = [
                        'related' => $selfUrl . '/' . $happ->id. '/categories',
                    ];
                }
                if(count($tags) > 0) {
                    $data[$key]['relationships']['tags'] = $tags;
                    $data[$key]['relationships']['tags']['links'] = [
                        'related' => $selfUrl . '/' . $happ->id. '/tags',
                    ];
                }
                if(isset($venue)) {
                    $data[$key]['relationships']['venue'] = $venue;
                    $data[$key]['relationships']['venue']['links'] = [
                        'related' => $selfUrl . '/' . $happ->id. '/venue',
                    ];
                }

            }

            $result['happs'] = [];

            if(count($data) > 0) {
                $result['happs']['data'] = $data;
            }

            if(count($data) == 1) {
                $result['happs']['links'] = [
                    'self' => $selfUrl . '/' . $data[0]['id'],
                ];
            }

            if(count($data) > 1) {
                $result['happs']['links'] = [
                    'self' => $selfUrl,
                ];
            }
        }

        $result['status_code'] = $statusCode;

        if(count($errors) > 0) {
            $result['happs']['errors'] = $errors;
        }

        $result['happs']['meta']['count'] = count($happs);

        return $result;
    }

    private static function jsonApiHappVenueStructure($happ = array(), $statusCode = Response::HTTP_NOT_FOUND, $errors = array('404' => 'Content not found'))
    {
        $selfUrl = url('/happs');

        $result = [];
        $data = [];
        if(count($happ) > 0) {
            if(isset($happ->venue_id) || isset($happ->venue_name) || isset($happ->venue_url)) {
                $data['data']['type'] = 'venues';
                $data['data']['id'] = $happ->venue_id;
                $data['data']['attributes'] = [
                    'name' => $happ->venue_name,
                    'url' => $happ->venue_url,
                ];
            }

            $data['links'] = [
                'self' => $selfUrl . '/' . $happ->id . '/venue'
            ];
        }

        $result['venue'] = [];

        if(count($data) > 0) {
            $result['venue'] = $data;
        }

        $result['status_code'] = $statusCode;

        if(count($errors) > 0) {
            $result['venue']['errors'] = $errors;
        }

        $result['venue']['meta']['count'] = count($happ);

        return $result;

    }

    public static function getHapps($happs)
    {
        if(count($happs) > 0) {

            return self::jsonApiHappStructure($happs, Response::HTTP_OK, array());
        }

        return self::jsonApiHappStructure();
    }



    public static function getHappVenue($happ)
    {
        if(count($happ) > 0) {

            return self::jsonApiHappVenueStructure($happ, Response::HTTP_OK, array());
        }

        return self::jsonApiHappVenueStructure();
    }

} 