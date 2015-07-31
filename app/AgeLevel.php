<?php

namespace CityHapps;

use Illuminate\Database\Eloquent\Model;

class AgeLevel extends Model {

	protected $fillable = ['name'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'age_level';

    public function happ()
    {
        return $this->belongsToMany('HappAgeLevel');
    }

}
