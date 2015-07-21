<?php

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Tag extends Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = [
      'build_from' => 'tag_raw',
      'save_to'    => 'tag_url',
    ];

    protected $fillable = array('tag_raw', 'tag_url');

    public function happs(){
        return $this->belongsToMany('Happ');
    }

    public function venues(){
        return $this->belongsToMany('Venue');
    }
}
