<?php

namespace CityHapps;

//use Illuminate\Database\Eloquent\Model;
use EchoIt\JsonApi\Model;

class AgeLevel extends Model {

	protected $fillable = ['name'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'age_level';

    public function happs()
    {
        return $this->belongsToMany('CityHapps\Happ', 'happ_age_level', 'age_level_id', 'happ_id');
    }

}
