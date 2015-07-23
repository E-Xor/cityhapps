<?php

class HappFilterHelper {

    private static $times = [
            'morning' => [
                'from' => '06:00:00',
                'to' => '11:59:00',
            ],
            'afternoon' => [
                'from' => '12:00:00',
                'to' => '16:59:00',
            ],
            'evening' => [
                'from' => '17:00:00',
                'to' => '20:59:00',
            ],
            'night' => [
                'from' => '21:00:00',
                'to' => '05:59:00',
            ],
        ];

    public static function filterZipRadius($happs, $zipRadius) {

        //TODO: some zipRadius Logic

        return $happs;
    }

    public static function filterDate($happs, $date)
    {
        $result = [];
        foreach($happs as $happ) {

            if(is_null($happ->end_time)) {

                $date = new DateTime($date);
                $date = $date->format('Y-m-d');

                $startDate = new DateTime($happ->start_time);
                $startDate = $startDate->format('Y-m-d');

                if($date == $startDate) {
                    $result[] = $happ;
                }
            } else {

                $date = new DateTime($date);
                $date = $date->format('Y-m-d');

                $startDate = new DateTime($happ->start_time);
                $startDate = $startDate->format('Y-m-d');

                $endDate = new DateTime($happ->end_time);
                $endDate = $endDate->format('Y-m-d');

                if($startDate <= $date && $date <= $endDate) {
                    $result[] = $happ;
                }

            }
        }

        return $result;
    }

    public static function filterTimeOfDay($happs, $timeOfDay)
    {
        $time = self::convertTimeOfDay($timeOfDay);

        $result = [];

        foreach($happs as $happ) {

            if(isset($happ->start_time) && !is_null($time)) {

                $startTime = new DateTime($happ->start_time);
                $startTime = $startTime->format('H:i:s');

                if($time['from'] <= $startTime && $startTime <= $time['to']) {
                    $result[] = $happ;
                }
            }
        }

        return $result;
    }


    private static function convertTimeOfDay($timeOfDay)
    {
        if(array_key_exists($timeOfDay, self::$times)) {

            return self::$times[$timeOfDay];
        }

        return null;
    }
} 