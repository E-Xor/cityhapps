<?php

use Illuminate\Support\Facades\DB;

class HappRecurring extends \Eloquent
{
    protected $guarded = array('id', 'start_date', 'end_date');


    protected  $table = 'happs_recurring';

    public function happ()
    {
        return $this->belongsTo('Happ');
    }
}
