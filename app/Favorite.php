<?php

namespace CityHapps;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';

    public function events(){
        return $this->hasMany('Happ', 'event_id', 'id');
    }

    public function users(){
        return $this->hasMany('User', 'user_id', 'id');
    }
}
