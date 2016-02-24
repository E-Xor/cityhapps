<?php
namespace CityHapps;

use Storage;

class ResourceImageUploader {
    const PATH = '/uploads/images/';

    public function __construct($dataUri, $resource, $type = 'event') {
        $this->dataUri = $this->parseData($dataUri);
        $this->resource = $resource;
        $this->type = $type;
    }

    public function save() {
        $path = static::PATH.$this->type.'s/'.$this->resource->id.'/'.time().'.png';
        if (!$this->dataUri || false === \Storage::disk('public')->put($path, $this->dataUri)) {
            return false;
        } else {
            return $path;
        }
    }

    private function parseData($dataUri) {
        $data = substr($dataUri, strpos($dataUri, ',') + 1);
        return base64_decode(str_replace(' ', '+', $data));
    }
}