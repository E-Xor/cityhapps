<?php

class HappAgeLevel extends \Eloquent {
	protected $fillable = [];
	protected $table = 'happ_age_level';

	function event(){
		return $this->belongsToMany('Happ');
	}
}
