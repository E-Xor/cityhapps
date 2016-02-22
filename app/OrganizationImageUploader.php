<?php
namespace CityHapps;

use Storage;

class OrganizationImageUploader {
    const PATH = '/uploads/images/events/';

    public function __construct($dataUri, $event) {
        $this->dataUri = $this->parseData($dataUri);
        $this->event = $event;
    }

    public function save() {
        $path = static::PATH.$this->event->id.'/org-'.time().'.png';
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