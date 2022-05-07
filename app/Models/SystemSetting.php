<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;
    protected $table = 'system_setting';
    protected static array $settings = [];

    public static function setSetting($name, $value, $type = 'system'): ?SystemSetting
    {
        try{
            $setting = self::whereName($name)->whereType($type)->first();

            if($setting instanceof SystemSetting) {
                $setting->value = $value;
                $setting->save();

                self::$settings[$name.$type] = $value;
                return $setting;
            }

            $setting = new self;
            $setting->name = $name;
            $setting->type = $type;
            $setting->value = $value;
            $setting->save();

            self::$settings[$name.$type] = $value;
            return $setting;
        }catch (Exception $exception) {
            return null;
        }
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
