<?php

class Tag extends Eloquent
{
    protected $fillable = array('tag_raw', 'tag_url');

    public function happs(){
        return $this->belongsToMany('Happ');
    }

    public function venues(){
        return $this->belongsToMany('Venue');
    }
}
