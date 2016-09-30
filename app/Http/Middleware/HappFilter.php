<?php

namespace CityHapps\Http\Middleware;

class HappFilter
{

    /**
     * @var array of available Times of Day
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
     * @param object $model model
     * @param string $key column name
     * @param string $value column value
     */
    public static function filterByHappColumn(&$model, $key, $value)
    {
        $values = explode(',', $value);

        if(count($values) > 1) {
            $model->where(function ($model) use ($key, $values) {
                    foreach ($values as $val) {
                        $model->orWhere($key, '=', $val);
                    }
                }
            );
        } else {
            $model->where($key, '=', $value);
        }
    }

    /**
     * @param object $model model
     * @param string $key column name
     * @param string $value column value
     */
    public static function filterLocationType(&$model, $value)
    {
        $model->whereRaw('(location_type = \'' . ucwords($value) . '\' OR location_type IS NULL)');
    }

    /**
     * @param $query
     * @param $values
     */
    public static function filterZipRadius(&$query, $values)
    {
//        $whereClause = '';
//        $whereValues = array();
//
//        $zipRadiuses = explode(',', $values);
//
//        foreach($zipRadiuses as $zipRadius) {
//
//            if (!empty($whereClause)) {
//                $whereClause .= ' OR ';
//            }
//            $whereClause .= '(';
//
//            $whereClause .= ')';
//        }
//
//        if (count($zipRadiuses) > 1)
//            $whereClause = '(' . $whereClause . ')';
//
//        $query->whereRaw($whereClause, $whereValues);

    }

    /**
     * @param $query
     * @param $values
     *
     */
    public static function filterAgeLevel(&$query, $values)
    {
        $whereClause = '';
        $whereValues = array();

        $ageLevels = explode(',', $values);

        foreach($ageLevels as $ageLevel) {

            if (!empty($whereClause)) {
                $whereClause .= ' OR ';
            }

            $whereClause .= '(age_level.id = ?)';
            $whereValues[] = $ageLevel;

        }

        if (count($ageLevels) > 1)
            $whereClause = '(' . $whereClause . ')';

        $query->whereHas('ageLevels', function($q) use($whereClause, $whereValues) {

                $q->whereRaw($whereClause, $whereValues);

            });
    }

    /**
     * @param $query
     * @param $value
     */
    public static function filterDate(&$query, $value)
    {
        $whereClause = '';
        $whereValues = array();

        // Handle '>' first
        if ($value[0] == '>') {
            $date = substr($value, 1); // remove first '>'
            $date = date('Y-m-d', strtotime($date));
            $start = $date . ' 00:00:00';

            if (!empty($whereClause)) {
                $whereClause .= ' OR ';
            }
            $whereClause .= '(';
            $whereClause .= '(start_time >= ? OR end_time >= ?)';
            $whereValues[] = $start;
            $whereValues[] = $start;

            // Add clause for no start/end times
            $whereClause .= ' OR (start_time IS NULL OR event_date IS NULL)';
            $whereClause .= ')';
        }
        else {
            // Handle comma separated multiple values
            $dates = explode(',', $value);
            foreach ($dates as $date) {
                $date = date('Y-m-d', strtotime($date));
                $start = $date . ' 00:00:00';
                $end = $date . ' 23:59:59';

                if (!empty($whereClause)) {
                    $whereClause .= ' OR ';
                }
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

                // Add clause for no start/end times
                $whereClause .= ' OR (start_time IS NULL OR event_date IS NULL)';
                $whereClause .= ')';
            }

            if (count($dates) > 1)
                $whereClause = '(' . $whereClause . ')';
        }

        $query->whereRaw($whereClause, $whereValues);
    }

    /**
     * @param $query
     * @param $values
     *
     */
    public static function filterTimeOfDay(&$query, $values)
    {
        $timesOfDay = explode(',', $values);

        $times = [];
        foreach ($timesOfDay as $time) {
            if (array_key_exists($time, self::$times)) {
                $times[] = $time;
            }
        }

        $whereClause = '';
        $whereValues = array();

        foreach ($times as $time) {
            $time = self::convertTimeOfDay($time);

            $from = $time['from'];
            $to = $time['to'];

            if (!empty($whereClause)) {
                $whereClause .= ' OR ';
            }
            $whereClause .= '(';

            $whereClause .= '(DATE_FORMAT(start_time, "%T") >= ? AND DATE_FORMAT(start_time, "%T") <= ?)';
            $whereValues[] = $from;
            $whereValues[] = $to;

            $whereClause .= ')';
        }
        if (count($times) > 1)
            $whereClause = '(' . $whereClause . ')';


        $query->whereRaw($whereClause, $whereValues);
    }

    /**
     * @param $timeOfDay
     *
     * @return null || array self::$times[$timeOfDay]
     */
    private static function convertTimeOfDay($timeOfDay)
    {
        if (array_key_exists($timeOfDay, self::$times)) {
            return self::$times[$timeOfDay];
        }

        return null;
    }

    /**
     * @param $query
     * @param $values
     */
    public static function filterSearch(&$query, $values)
    {
        $whereClause = '';
        $whereValues = array();

        $words = explode(' ', $values);

        foreach($words as $word) {

            if (!empty($whereClause)) {
                $whereClause .= ' AND ';
            }

            $whereClause .= '(description LIKE ? OR event_name LIKE ?)';
            $whereValues[] = '%'.$word.'%';
            $whereValues[] = '%'.$word.'%';

        }

        if (count($words) > 1)
            $whereClause = '(' . $whereClause . ')';

        $query->whereRaw($whereClause, $whereValues);
    }
}