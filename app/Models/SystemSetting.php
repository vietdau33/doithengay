<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;
    protected $table = 'system_setting';
    protected static array $settings = [];

    public static function setSetting($name, $value, $type = 'system'): SystemSetting
    {
        $setting = self::getSetting($name, $type);
        self::$settings[$name.$type] = $value;

        if($setting instanceof SystemSetting) {
            $setting->value = $value;
            $setting->save();
            return $setting;
        }

        $setting = new self;
        $setting->name = $name;
        $setting->type = $type;
        $setting->value = $value;
        $setting->save();

        return $setting;
    }

    public static function getSetting($name, $type = 'system', $default = null): ?string
    {
        if(isset(self::$settings[$name.$type])){
            return self::$settings[$name.$type];
        }
        self::$settings[$name.$type] = self::whereName($name)->whereType($type)->first()->value ?? $default;
        return self::$settings[$name.$type];
    }

    public static function getAllSetting($type = 'system'){
        $allSetting = self::whereType($type)->get()->toArray();
        if(count($allSetting) == 0) {
            return $allSetting;
        }
        return array_reduce($allSetting, function($result, $setting){
            $result[$setting['name']] = $setting['value'];
            return $result;
        }, []);
    }
}
