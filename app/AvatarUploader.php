<?php
namespace CityHapps;

use Storage;

class AvatarUploader {
    const PATH = '/uploads/images/avatars/';

    public function __construct($dataUri, $user) {
        $this->dataUri = $this->parseData($dataUri);
        $this->user = $user;
    }

    public function save() {
        $path = static::PATH.$this->user->id.'/'.time().'.png';
        if (false === \Storage::disk('public')->put($path, $this->dataUri)) {
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