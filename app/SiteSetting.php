<?php
namespace CityHapps;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model {
    protected $fillable = ['key'];

    public static function findKey($key) {
        return static::where('key', $key)->first();
    }

    public static function setKey($key, $value) {
        $setting = static::findKey($key) ?: new static(['key' => $key]);
        $setting->value = $value;
        if ($setting->save()) {
            return $setting;
        } else {
            throw new \Exception("Could not update setting {$key}");
        }
    }

    public static function allMap() {
        return static::all()->reduce(function($memo, $setting) {
            $memo[$setting->key] = $setting->value;
            return $memo;
        }, []);
    }
}