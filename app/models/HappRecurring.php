<?php

use Illuminate\Support\Facades\DB;

class HappRecurring extends \Eloquent
{
    protected $guarded = array('id', 'happ_id');


    protected  $table = 'happs_recurring';

    public function happ()
    {
        return $this->belongsTo('Happ');
    }
}
