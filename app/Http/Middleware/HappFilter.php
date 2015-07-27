<?php

namespace CityHapps\Http\Middleware;

class HappFilter 
{

  /**
   *
   */
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

  /**
   *
   */
  public static function filterZipRadius($happs, $zipRadius)
  {
    //TODO: some zipRadius Logic
    return $happs;
  }

  /**
   *
   */
  public static function filterDate(&$query, $value)
  {
    $whereClause = '';
    $whereValues = array();

    // Handle comma separated multiple values
    $dates = explode(',', $value);
    foreach($dates as $date) {
      $date = date('Y-m-d', strtotime($date));
      $start = $date . ' 00:00:00';
      $end = $date . ' 23:59:59';

      if (!empty($whereClause))
        $whereClause .= ' OR ';
      $whereClause .= '(';

      // Add the clause where end_time is NULL
      $whereClause .= '(start_time >= ? AND start_time <= ? AND end_time IS NULL)';
      $whereValues[] = $start;
      $whereValues[] = $end;

      $whereClause .= ' OR ';

      // Add the clause for overlapping time spans
      $whereClause .= '(start_time <= ? AND end_time >= ?)';
      $whereValues[] = $end;
      $whereValues[] = $start;

      $whereClause .= ')';
    }

    if (count($dates) > 1)
      $whereClause = '(' . $whereClause . ')';

    $query->whereRaw($whereClause, $whereValues);
  }

  /**
   *
   */
  public static function filterTimeOfDay($happs, $timeOfDay)
  {
    $time = self::convertTimeOfDay($timeOfDay);
    $result = [];
    foreach($happs as $happ) {

      if(isset($happ->start_time) && !is_null($time)) {

        $startTime = new \DateTime($happ->start_time);
        $startTime = $startTime->format('H:i:s');

        if($time['from'] <= $startTime && $startTime <= $time['to']) {
          $result[] = $happ;
        }
      }
    }
    return $result;
  }

  /**
   *
   */
  private static function convertTimeOfDay($timeOfDay)
  {
    if(array_key_exists($timeOfDay, self::$times)) {
      return self::$times[$timeOfDay];
    }

    return null;
  }
} 