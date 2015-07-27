<?php

namespace CityHapps;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Tag extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = [
      'build_from' => 'tag_raw',
      'save_to'    => 'tag_url',
    ];

    protected $fillable = array('tag_raw', 'tag_url');

    public function happs(){
        return $this->belongsToMany('CityHapps\Happ');
    }

    public function venues(){
        return $this->belongsToMany('Venue');
    }
}
