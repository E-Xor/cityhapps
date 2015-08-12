<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use CityHapps\Tag;

class TagController extends Controller
{

    /**
     * Returns a list of matching tags based on the name
     */
    function getTags($name)
    {
        $tags = [];
        $results = Tag::where('tag_raw', 'LIKE', "%{$name}%")->get();
        foreach ($results as $tag) {
            $tags[] = [
              "id" => $tag->id,
              "tag_raw" => $tag->tag_raw
            ];
        }
        return $tags;
    }
}
